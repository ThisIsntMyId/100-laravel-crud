<?php

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

use App\Category;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cats', function () {
    $categories = Category::with('recursiveChildren')->whereNull('parent_id')->get();
    // return $categories;
    return view('cats', ['categories' => $categories]);
});

Route::group(['middleware' => 'web'], function () {
    Route::get(env('LARAVUE_PATH'), 'LaravueController@index')->where('any', '.*')->name('laravue');
});
