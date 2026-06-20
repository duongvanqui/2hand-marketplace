<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(Request $request)
    {
        if (!Auth::check()) return response()->json(['error' => 'Unauthenticated'], 401);
        
        $userToFollow = User::findOrFail($request->user_id);
        $auth = Auth::user();

        if ($auth->id === $userToFollow->id) return response()->json(['error' => 'Cannot follow self'], 400);

        $isFollowing = $auth->followings()->where('following_id', $userToFollow->id)->exists();

        if ($isFollowing) {
            $auth->followings()->detach($userToFollow->id);
            $isFollowing = false;
        } else {
            $auth->followings()->attach($userToFollow->id);
            $isFollowing = true;
        }

        return response()->json([
            'isFollowing' => $isFollowing,
            'followersCount' => $userToFollow->followers()->count()
        ]);
    }
}