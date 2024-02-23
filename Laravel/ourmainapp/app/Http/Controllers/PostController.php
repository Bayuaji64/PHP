<?php
// php artisan make:controller PostController

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
// use App\Mail\NewPostEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{


    public function showCreateForm()
    {


        return view('create-post');
    }


    public function storeNewPost(Request $request)
    {

        $incomingFields = $request->validate([

            'title' => 'required',
            'body' => 'required'
        ]);


        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();


        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        // Mail::to(auth()->user()->email)->send(new NewPostEmail(['name' => auth()->user()->username, 'title' => $newPost->title]));

        return redirect("/post/{$newPost->id}")->with('success', 'New post successfully created');
    }


	public function storeNewPostApi(Request $request)
    {

        $incomingFields = $request->validate([

            'title' => 'required',
            'body' => 'required'
        ]);


        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();


        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        // Mail::to(auth()->user()->email)->send(new NewPostEmail(['name' => auth()->user()->username, 'title' => $newPost->title]));

        return $newPost->id;
    }
    public function viewSinglePost(Post $postId)
    {

        // if ($postId->user_id === auth()->user()->id) {

        //     return 'you are the author';
        // }


        // return 'you are not the author';


        $postId['body'] = strip_tags(Str::markdown($postId->body), '<p><ul><li><strong><em><h3><br>');

        // return $userId->title;
        return view('single-post', ['postData' => $postId]);
    }


    public function deletePost(Post $postId)
    {


        // if (auth()->user()->cannot('delete', $postId)) {
        //     return 'You cannot do that';
        // }


        $postId->delete();


        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successfully deleted.');
    }

	public function deleteApi(Post $postId)
    {


        // if (auth()->user()->cannot('delete', $postId)) {
        //     return 'You cannot do that';
        // }


        $postId->delete();


        return 'true';
    }

    public function showEditPost(Post $postId)
    {

        return view('edit-post', ['postData' => $postId]);
    }

    public function EditPost(Post $postId, Request $request)
    {
        $incomingFields = $request->validate([

            'title' => 'required',
            'body' => 'required'

        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $postId->update($incomingFields);

        return back()->with('success', 'Post successfully updated.');
    }


    public function search($term)
    {

        $posts = Post::search($term)->get();

        $posts->load('userData:id,username,avatar');

        return $posts;

        // composer require laravel/scout
        // php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"


    }
}
