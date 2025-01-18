<?php

namespace App\Http\Controllers;

use App\Models\PostAttachment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $page = $request->input("page",0);
        $size = $request->input("size",10);

        if(!is_numeric($page) || $page < 0) {
            return response ()->json([ 
            'message' => 'invalid field',
            'errors' => [
                'page' => 'the page field must be at least 0',
                'size' => 'the size must be a number'
            ]    
            ]);
        }

        $query = Post::whereHas('User', )->with('user', 'postsa')->orderBy('id', 'asc');



        // $query->with('User');

        $post = $query->paginate($size, ['*'], 'page', $page);

        return response()->json([
            'page' => $post->currentPage() ,
            'size' => $post->perPage(),
            'post' => $post->map(function ($post) {
                return [
                    'id'=> $post->id,
                    'caption' => $post->caption,
                    'created_at' => $post->created_at,
                    'deleted_at' => $post->deleted_at || null,
                    'user' => [
                        'id' => $post->user->id,
                        'full_name' => $post->user->full_name,
                        'username' => $post->user->username,
                        'bio' => $post->user->bio,
                        'is_private' => $post->user->is_private,
                        'created_at' => $post->user->created_at,
                    ],
                    'attachments' => $post->postsa->map(function ($attachment) {
                        return [
                            'id' => $attachment->id,
                            'storage_path' => $attachment->storage_path,
                        ];
                    }),
                   
                ];

            })
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'caption' => 'required|string|max:255',
            'attachments' =>'required|array',
            'attachments.*' => 'sometimes|file|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);

        $post = Post::create([
            'caption' => $validated['caption'],
            'user_id' =>Auth::id()
        ]);

        $attachments = [];

        foreach( $validated['attachments'] as $file ) {
            $path = $file->store('posts', 'public');
            $attachments[] = [
                'post_id' => $post->id,
                'storage_path' => $path,
            ];
        }

        PostAttachment::insert($attachments);

        return response()->json([
            'message' => 'post created Successfully'
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        
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
        $posts = Post::find($id);

        if(!$posts) {
            return response()->json([
                'message' => 'Post not Found'
            ], 404);
        }

        if($posts->user_id !== Auth::id()) { 
            return response()->json([ 
                'message'=> 'Forbidden Access'
            ]);
        }
        $posts->delete();

        return response()->json([ 'message' => 'Post Deleted Successfully' ]);

    }
}
