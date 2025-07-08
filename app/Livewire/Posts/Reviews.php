<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Reviews extends Component
{
    public Post $post;
    public string $review = '';
    public int $rating = 5;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    protected $rules = [
        'review' => 'required|string|min:3',
        'rating' => 'required|integer|min:1|max:5',
    ];

    public function submit()
    {
        $this->validate();

        Review::create([
            'user_id' => Auth::id(),
            'post_id' => $this->post->id,
            'review' => $this->review,
            'rating' => $this->rating,
        ]);

        $this->reset(['review', 'rating']);
        session()->flash('message', 'Review submitted!');
    }

    public function render()
    {
        $reviews = $this->post->reviews()->with('user')->latest()->get();

        return view('livewire.posts.reviews', [
            'reviews' => $reviews,
        ]);
    }
}
