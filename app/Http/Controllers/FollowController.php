<?php

namespace App\Http\Controllers;

use App\Models\follow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $username)
    {
        //

        $LoginUser = Auth::user();
        // dd($LoginUser);
        $targetUser = User::where('username', $username)->first();
        // dd($targetUser);

        if(!$targetUser) {
            return response()->json([
                'message' => 'User not Found'
            ],401);
        }

        if ($LoginUser->id === $targetUser->id) { 
            return response()->json([
                'message' => 'Cannot Follow yourself'
            ],422);
        }

        $exists = follow::where('follower_id', $LoginUser->id)->where('following_id', $targetUser->id)->first();
        if($exists) {
            return response()->json([ 
                'message' =>'You Are Already Follow That User!',
                'status' => $exists->is_accepted ? 'following' : 'requested',           
            ], 422);
        }

        $follow = Follow::create([
            'follower_id' => $LoginUser->id,
            'following_id' => $targetUser->id,
            'is_accepted' => $targetUser->is_private ? 0 : 1,
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => 'success',
            'status' => $follow->is_accepted ?'following':'request',
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show($username)
    {
        //
        $Login = Auth::user();
        // $targetUser = User::where('username', $username)->get();

        $follow = $Login->follower()->with('following')->paginate();
        // dd($follow);
        return response()->json([ 
            'followers' => $follow->map(function($follow) {
                return [
                    'username' => $follow->following->username,
                    'full_name' => $follow->following->full_name,
                    'id' => $follow->following->id,
                    'bio' => $follow->following->bio,
                    'is_private' => $follow->following->is_private,
                    'created_at' => $follow->created_at,
                    'is_requested' => $follow->is_accepted,
                ];
            })
        ], 200);    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $username)
    {
        //
        $user = Auth::user();

    // Cari pengguna yang ingin diterima permintaannya
    $follower = User::where('username', $username)->first();

    if (!$follower) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    $follow = $user->follower()->where('follower_id', $follower->id)->first();

    if (!$follow) {
        return response()->json([
            'message' => 'The user is not following you'
        ], 422);
    }

    if ($follow->is_accepted) {
        return response()->json([
            'message' => 'You have already accepted this follow request'
        ], 422);
    }

    // Perbarui status is_accepted
    $follow->update(['is_accepted' => true]);

    return response()->json([
        'message' => 'Follow request accepted'
    ], 200);



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $username)
    {
        //
        $Login = Auth::user();
        $target =  User::where('username', $username)->first();

        if(!$target) {
            return response()->json([
               'message' => 'User Not found'
            ]);
        }

        $follow = follow::where('follower_id', $Login->id)->where('following_id', $target->id)->first();

        if(!$follow) {
            return response()->json([
                'message' => 'Youre not following this user'
            ]);
        }

        $follow->delete();  

        return response()->json([], 204);
    }
}
