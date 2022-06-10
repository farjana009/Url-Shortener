<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

 Route::get('/', function () {
    return view('shortenUrl');
});

Route::get('get-short-urls', [ShortUrlController::class, 'index']);

Route::post('link-short', [ShortUrlController::class, 'linkShortStore']);

//Route::get('{code}', [ShortUrlController::class, 'shortenUrl'])->name('short.url');
Route::get('{code}', [ShortUrlController::class, 'shortenUrl']);
