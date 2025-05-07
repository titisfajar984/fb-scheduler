<?php

namespace App\Http\Controllers;

use App\Models\FbPage;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        $pages = FbPage::whereHas('account', function($query) {
            $query->where('user_id', Auth::id());
        })->with('account')->get();

        return view('pages.index', compact('pages'));
    }
}
