<?php

use App\Events\ChatMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use Illuminate\Http\Request;

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

Route::get('/admin-only', function () {

    // if (Gate::allows('visitAdminPages')) {

    //     return 'Only ADMIN';

    //     # code...
    // }

    return 'you cannot view this page';
})->middleware('can:visitAdminPages');

//User related routes
Route::get('/', [UserController::class, "showCorrectHomePage"])->name('login');
Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"])->middleware('guest');
Route::post('/logout', [UserController::class, "logout"])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar', [UserController::class, "showAvatar"])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, "storeAvatar"])->middleware('mustBeLoggedIn');



//Follow related routes

Route::post('/create-follow/{userId:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{userId:username}', [FollowController::class, 'removeFollow'])->middleware('mustBeLoggedIn');

//Blog post related routes
Route::get('/create-post', [PostController::class, "showCreateForm"])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, "storeNewPost"])->middleware('mustBeLoggedIn');
Route::get('/post/{postId}', [PostController::class, "viewSinglePost"]);

Route::get('/post/{postId}/edit', [PostController::class, "showEditPost"])->middleware('can:update,postId');
Route::put('/post/{postId}', [PostController::class, "EditPost"])->middleware('can:update,postId');

Route::delete('/post/{postId}', [PostController::class, "deletePost"])->middleware('can:delete,postId');
Route::get('/search/{term}', [PostController::class, 'search']);




//profile related routes 


Route::get('/profile/{userId:username}', [UserController::class, "profile"]);
Route::get('/profile/{userId:username}/followers', [UserController::class, "profileFollowers"]);
Route::get('/profile/{userId:username}/following', [UserController::class, "profileFollowing"]);


Route::middleware('cache.headers:public;max_age=20;etag')->group(function () {

    Route::get('/profile/{userId:username}/raw', [UserController::class, "profileRaw"]);
    Route::get('/profile/{userId:username}/followers/raw', [UserController::class, "profileFollowersRaw"]);
    Route::get('/profile/{userId:username}/following/raw', [UserController::class, "profileFollowingRaw"]);
});

//chat routes

Route::post('/send-chat-message', function (Request $request) {
    // composer require pusher/pusher-php-server

    $formFields = $request->validate([
        'textvalue' => 'required'
    ]);

    if (!trim(strip_tags($formFields['textvalue']))) {
        return response()->noContent();
        # code...
    }

    broadcast(new ChatMessage(['username' => auth()->user()->username, 'textvalue' => strip_tags($request->textvalue), 'avatar' => auth()->user()->avatar]))->toOthers();

    return response()->noContent();
})->middleware('mustBeLoggedIn');
