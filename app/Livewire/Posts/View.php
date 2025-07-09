<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class View extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        if (!auth()->user()->can('view', $post)) {
            return $this->redirect(URL::previous(), navigate: true);
        }
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
