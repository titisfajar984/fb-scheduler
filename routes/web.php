<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Facebook Auth
Route::get('/auth/facebook', [FacebookAuthController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'handleFacebookCallback']);

// Pages
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');

// Posts
Route::resource('/posts', PostController::class)->except(['edit', 'update']);

// Import
Route::get('/import', [ImportController::class, 'showImportForm'])->name('import.form');
Route::post('/import', [ImportController::class, 'import'])->name('import.process');
