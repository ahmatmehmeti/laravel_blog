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

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


Auth::routes();
Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('locale/{locale}', function ($locale) {
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});


Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('posts/search', 'PostController@search')->name('search');
//Posts
    Route::get('get.posts', 'PostController@getPosts')->name('get.posts');
    Route::get('posts/unapproved', ['as' => 'posts.unapproved', 'uses' => 'PostController@unApproved'])->middleware('admin');
    Route::get('post/approve/{id}', ['as' => 'post.approve', 'uses' => 'PostController@approve'])->middleware('admin');

    Route::get('post/disapprove/{id}', ['as' => 'post.disapprove', 'uses' => 'PostController@disapprove'])->middleware('admin');

    Route::get('post/{slug}', ['as' => 'post.single', 'uses' => 'PostController@getSingle']);
    Route::resource('posts', 'PostController');


//Categories
    Route::get('get.categories', 'CategoryController@getCategories')->name('get.categories');

    Route::get('categories/{name}', ['as' => 'categories.name', 'uses' => 'CategoryController@categoriesName']);
    Route::resource('categories', 'CategoryController', ['except' => ['create']])->middleware('admin');
//Tags
    Route::get('get.tags', 'TagController@getTags')->name('get.tags');
    Route::resource('tags', 'TagController', ['except' => ['create']])->middleware('admin');

//Comments
    Route::get('get.comments', 'CommentController@getComments')->name('get.comments');
    Route::get('comments/approve/{id}', ['as' => 'comments.approve', 'uses' => 'CommentController@approve']);
    Route::get('comments/disapprove/{id}', ['as' => 'comments.disapprove', 'uses' => 'CommentController@disapprove']);

    Route::post('comments/{post_id}', ['uses' => 'CommentController@store', 'as' => 'comments.store']);
    Route::get('comments', ['as' => 'comments.index', 'uses' => 'CommentController@index']);
    Route::get('comments/{id}/edit', ['uses' => 'CommentController@edit', 'as' => 'comments.edit']);
    Route::put('comments/{id}', ['uses' => 'CommentController@update', 'as' => 'comments.update']);
    Route::delete('comments/{id}', ['uses' => 'CommentController@destroy', 'as' => 'comments.destroy']);

});


