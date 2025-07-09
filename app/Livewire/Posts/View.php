<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class View extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        $reviews = $this->post->reviews()->with('user')->latest()->get();
        return view('livewire.posts.view', [
            'reviews' => $reviews,
        ]);
    }
}
