<?php

namespace App\Http\Controllers;

use App\Models\ScheduledPost;
use App\Models\FbPage;
use App\Services\FacebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function index()
    {
        $posts = ScheduledPost::where('user_id', Auth::id())
            ->with('page')
            ->orderBy('scheduled_time', 'desc')
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $pages = FbPage::whereHas('account', function($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('posts.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fb_page_id' => 'required|exists:fb_pages,id',
            'caption' => 'required|string|max:2000',
            'scheduled_time' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi|max:51200',
            'link_url' => 'nullable|url',
        ]);

        $postData = [
            'fb_page_id' => $request->fb_page_id,
            'user_id' => Auth::id(),
            'caption' => $request->caption,
            'scheduled_time' => $request->scheduled_time,
            'status' => 'pending',
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/post_images');
            $postData['image_url'] = Storage::url($path);
        } elseif ($request->hasFile('video')) {
            $path = $request->file('video')->store('public/post_videos');
            $postData['video_url'] = Storage::url($path);
        } elseif ($request->link_url) {
            $postData['link_url'] = $request->link_url;
        }

        ScheduledPost::create($postData);

        return redirect()->route('posts.index')->with('success', 'Post scheduled successfully!');
    }

    public function show($id)
    {
        $post = ScheduledPost::where('user_id', Auth::id())->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function destroy($id)
    {
        $post = ScheduledPost::where('user_id', Auth::id())->findOrFail($id);

        if ($post->image_url) {
            $path = str_replace('/storage', 'public', $post->image_url);
            Storage::delete($path);
        }

        if ($post->video_url) {
            $path = str_replace('/storage', 'public', $post->video_url);
            Storage::delete($path);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
}
