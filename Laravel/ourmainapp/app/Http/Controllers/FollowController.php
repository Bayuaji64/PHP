<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{

    public function createFollow(User $userId)
    {

        if ($userId->id == auth()->user()->id) {
            return back()->with('failure', 'You cannot follow yourself.');
        }

        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $userId->id]])->count();

        if ($existCheck) {
            return back()->with('failure', 'You are already following that user.');
        }

        $newFollow = new Follow;

        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $userId->id;
        $newFollow->save();

        return back()->with('success', 'User successfully followed.');
    }

    public function removeFollow(User $userId)
    {

        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $userId->id]])->delete();
        return back()->with('success', 'User succesfully unfollowed.');
    }
}
