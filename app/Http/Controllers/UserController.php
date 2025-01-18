<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $login = Auth::user();
        $user = User::where('id', '!=', $login->id)->get();

            return response()->json([
                'message' => 'get data Successfully',
                'user' => $user,
            ]);

            // if(!$user) {
            //     return response()->json([
            //         'message' => ''
            //     ])
            // }


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $username)
    {
        //
       
        
    }

    /**
     * Display the specified resource.
     */
    public function show($username)
    {
        $Auth = Auth::user();
    
        $user = User::with(['posts.postsa'])->where('username', $username)->first();
    
        if (!$user) {
            return response()->json([
                'message' => 'User Not Found'
            ], 404);
        }
    
        $followStatus = 'not-following';        
        if ($Auth) {
            $followRequest = $Auth->following()->where('following_id', $user->id)->first();
            if ($followRequest) {
                $followStatus = $followRequest->is_accepted ? 'following' : 'requested';
            }
        }
    
        $followersCount = $user->follower()->count();
        $followingCount = $user->following()->count();
        $postCount = $user->posts()->count();
    
        $posts = [];
        if (!$user->is_private || $followStatus === 'following' || $user->id === $Auth?->id) {
            $posts = $user->posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'caption' => $post->caption,
                    'created_at' => $post->created_at,
                    'attachments' => $post->postsa->map(function ($attachment) {
                        return [
                            'id' => $attachment->id,
                            'storage_path' => $attachment->storage_path
                        ];
                    })
                ];
            });
        }
    
        // Kembalikan response dengan detail user
        return response()->json([
            'id' => $user->id,
            'full_name' => $user->full_name,
            'username' => $user->username,
            'bio' => $user->bio,
            'is_private' => $user->is_private,
            'created_at' => $user->created_at,
            'is_your_account' => $user->id === $Auth?->id,
            'following_status' => $followStatus,
            'posts_count' => $postCount,
            'followers_count' => $followersCount,
            'following_count' => $followingCount,
            'posts' => $posts
        ], 200);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
