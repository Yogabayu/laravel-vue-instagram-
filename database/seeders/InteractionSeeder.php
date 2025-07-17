<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        foreach ($posts as $post) {
            // Random like
            $likers = $users->random(rand(1, 5));
            foreach ($likers as $user) {
                Like::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                ]);
            }

            // Random comment
            for ($i = 0; $i < rand(1, 3); $i++) {
                $commenter = $users->random();
                Comment::create([
                    'user_id' => $commenter->id,
                    'post_id' => $post->id,
                    'comment_text' => fake()->sentence(),
                ]);
            }
        }
    }
}
