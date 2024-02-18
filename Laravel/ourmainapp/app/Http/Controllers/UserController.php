<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{

    public function login(Request $request)
    {

        $incomingFields = $request->validate([

            'loginusername' => 'required',
            'loginpassword' => 'required'


        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {

            $request->session()->regenerate();

            return redirect('/')->with('success', 'You have successfully logged in');
        } else {

            return redirect('/')->with('failure', 'Invalid login');
        }
    }
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        $incomingFields['password'] =  bcrypt($incomingFields['password']);
        //TANPA BCRYPT SEKARANG KE HASH



        $user = User::create($incomingFields);
        auth()->login($user);



        return redirect('/')->with('success', 'Thank you for creating an account');
    }


    public function showCorrectHomePage()
    {


        if (auth()->check()) {

            return view('homepage-feed');
            # code...
        } else {
            return view('homepage');
        }
    }


    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out');
    }


    private function getSharedData($userId)
    {
        $currentlyFollowing = 0;


        if (auth()->check()) {

            $currentlyFollowing =  Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $userId->id]])->count();
            # code...
        }

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avatar' => $userId->avatar, 'username' => $userId->username, 'postCount' => $userId->posts()->count()]);
    }



    public function profile(User $userId)
    {
        $this->getSharedData($userId);

        // $thePosts = $userId->posts()->get();
        // return $thePosts;
        // $currentlyFollowing = 0;


        // if (auth()->check()) {

        //     $currentlyFollowing =  Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $userId->id]])->count();
        //     # code...
        // }

        return view('profile-posts', ['posts' => $userId->posts()->latest()->get()]);
    }


    public function profileFollowers(User $userId)
    {

        $this->getSharedData($userId);
        // $thePosts = $userId->posts()->get();
        // return $thePosts;
        // $currentlyFollowing = 0;


        // if (auth()->check()) {

        //     $currentlyFollowing =  Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $userId->id]])->count();
        //     # code...
        // }

        return view('profile-followers', ['posts' => $userId->posts()->latest()->get()]);
    }


    public function profileFollowing(User $userId)
    {
        $this->getSharedData($userId);

        // $thePosts = $userId->posts()->get();
        // return $thePosts;
        // $currentlyFollowing = 0;


        // if (auth()->check()) {

        //     $currentlyFollowing =  Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $userId->id]])->count();
        //     # code...
        // }

        return view('profile-following', ['posts' => $userId->posts()->latest()->get()]);
    }
    public function showAvatar()
    {
        return view('avatar-form');
    }

    public function storeAvatar(Request $request)
    {
        //          php artisan storage:link 
        // composer require intervention/image
        // composer require intervention/image:2.7.2
        // composer remove intervention/image

        $request->validate([

            'avatar' => 'required|image|max:3000'
        ]);

        $user = auth()->user();

        $filename = $user->id . '-' . uniqid() . '.jpg';

        $imgData =  Image::make($request->file('avatar'))->fit(120)->encode('jpg');

        Storage::put('public/avatars/' . $filename, $imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;

        $user->save();

        if ($oldAvatar != "/fallback-avatar.jpg") {

            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
            # code...
        }

        return back()->with('success', 'Congrats on the new Avatar');






        // $request->file('avatar')->store('public/avatars');
    }
}
