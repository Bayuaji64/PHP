<?php
// php artisan make:controller PostController

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

        return redirect("/post/{$newPost->id}")->with('success', 'New post successfully created');
    }

    public function viewSinglePost(Post $postId)
    {


        $postId['body'] = strip_tags(Str::markdown($postId->body), '<p><ul><li><strong><em><h3><br>');

        // return $userId->title;
        return view('single-post', ['postData' => $postId]);
        # code...
    }
}
