<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function randomPost()
    {
        try {
            $post = Post::with('user', 'likes', 'comments')->where('visibility', 'public')->orderBy('created_at', 'desc')->limit(10)->inRandomOrder()->get();
            return ResponseHelper::successRes("success get post", $post);
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
    public function addPost(Request $request)
    {
        try {
            $request->validate([
                'caption' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = $request->file('image')->store('posts', 'public');

            $post = new Post();
            $post->user_id = auth()->id();
            $post->caption = $request->caption;
            $post->image_path = $imagePath;
            $post->save();

            return ResponseHelper::successRes("Post berhasil dibuat", $post);
        } catch (\Throwable $th) {
            return ResponseHelper::errorRes($th->getMessage());
        }
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $userId = auth()->id();

        $like = $post->likes()->where('user_id', $userId)->first();

        if ($like) {
            // Jika sudah like, hapus (unlike)
            $like->delete();
            return ResponseHelper::successRes("unliked post", $post);
        }

        // Jika belum like, tambahkan like
        $post->likes()->create([
            'user_id' => $userId,
        ]);

        return ResponseHelper::successRes("liked post", $post);
    }
}
