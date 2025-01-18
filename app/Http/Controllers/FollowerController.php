<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class FollowerController extends Controller
{
    //

    public function index($username)

        {
            $targetUser = User::where('username', $username)->first();
        
            if (!$targetUser) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }
        
            $followers = $targetUser->following()->with('following')->distinct()->get();
        
            
            if ($followers->isEmpty()) {
                return response()->json([
                    'message' => 'No followers found'
                ], 404);
            }
        
            return response()->json([
                'followers' => $followers->map(function ($follow) {
                    return [
                        'id' => $follow->follower->id,
                        'full_name' => $follow->follower->full_name,
                        'username' => $follow->follower->username,
                        'bio' => $follow->follower->bio,
                        'is_private' => $follow->follower->is_private,
                        'created_at' => $follow->created_at,
                        'is_requested' => !$follow->is_accepted, 
                    ];
                }),
            ], 200);
        }

}
