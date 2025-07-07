<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'tailwind';

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        Storage::disk('public')->delete($post->image);
        $post->delete();
        session()->flash('message', 'Post deleted successfully!');
    }

    public function render()
    {
        return view('livewire.posts.index', [
            'posts' => auth()->user()->posts()->latest()->paginate(10)
        ]);
    }
}
