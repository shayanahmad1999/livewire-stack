<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        if ($users->count() === 0) {
            $this->command->info('No users found, seeder aborted.');
            return;
        }

        foreach (range(1, 50) as $i) {
            $user = $users->random();
            $title = fake()->sentence(6, true);
            $slug = Str::slug($title) . '-' . Str::random(5);

            Post::create([
                'user_id' => $user->id,
                'title' => $title,
                'slug' => $slug,
                'content' => fake()->paragraphs(3, true),
                'image' => fake()->imageUrl(640, 480, 'posts', true),
            ]);
        }
    }
}
