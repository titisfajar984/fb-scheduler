<?php

namespace App\Http\Controllers;

use App\Models\FbPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\PostsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function showImportForm()
    {
        $pages = FbPage::whereHas('account', function($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('import.form', compact('pages'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'fb_page_id' => 'required|exists:fb_pages,id',
        ]);

        $import = new PostsImport($request->fb_page_id, Auth::id());

        try {
            Excel::import($import, $request->file('file'));
            return redirect()->route('posts.index')->with('success', 'Posts imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
