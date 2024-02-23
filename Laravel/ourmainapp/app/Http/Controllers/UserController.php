<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

	public function loginApi(Request $request){
	 
		$incomingFields = $request->validate([

            'username' => 'required',
            'password' => 'required'


        ]);

		if (auth()->attempt($incomingFields)) {
			# code...
			$user = User::where('username', $incomingFields['username'])->first();
			$token = $user -> createToken('ourapptoken')->plainTextToken;
			return $token;
		}

		return '';




	}

    public function login(Request $request)
    {

        $incomingFields = $request->validate([

            'loginusername' => 'required',
            'loginpassword' => 'required'


        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {

            $request->session()->regenerate();

            // event(new OurExampleEvent());

            event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));

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

            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(5)]);
            # code...
        } else {

			// if (Cache::has('postCount')) {
			// 	$postCount = Cache::get('postCount');
			// } else {
			// 	sleep(5);

			// 	$postCount = Post::count();
			// 	Cache::put('postCount',$postCount,20);

			// }

			$postCount = Cache::remember('postCount',20, function(){

				// sleep(5);
				return Post::count();


			});
            return view('homepage',['postCount'=>$postCount]);
        }
    }


    public function logout()
    {
        event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
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

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avatar' => $userId->avatar, 'username' => $userId->username, 'postCount' => $userId->posts()->count(), 'followerCount' => $userId->followers()->count(), 'followingCount' => $userId->followingTheseUsers()->count()]);
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

        return view('profile-posts', ['posts' => $userId->posts()->latest()->paginate(5)]);
    }


    public function profileFollowers(User $userId)
    {

        $this->getSharedData($userId);
        // return  $userId->followers()->latest()->get();
        // $thePosts = $userId->posts()->get();
        // return $thePosts;
        // $currentlyFollowing = 0;


        // if (auth()->check()) {

        //     $currentlyFollowing =  Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $userId->id]])->count();
        //     # code...
        // }

        return view('profile-followers', ['followers' => $userId->followers()->latest()->get()]);
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

        return view('profile-following', ['following' => $userId->followingTheseUsers()->latest()->get()]);
    }




    public function profileRaw(User $userId)
    {
        return response()->json(['theHTML' => view("profile-posts-only", ['posts' => $userId->posts()->latest()->get()])->render(), 'docTitle' => $userId->username . "'s Profile"]);
    }


    public function profileFollowersRaw(User $userId)
    {

        return response()->json(['theHTML' => view("profile-followers-only", ['followers' => $userId->followers()->latest()->get()])->render(), 'docTitle' => $userId->username . "'s Followers"]);
    }

    public function profileFollowingRaw(User $userId)
    {
        return response()->json(['theHTML' => view("profile-following-only", ['following' => $userId->followingTheseUsers()->latest()->get()])->render(), 'docTitle' => 'Whos ' . $userId->username . "Follows"]);
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
