<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
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
        $this->authorize('delete', $post);
        Storage::disk('public')->delete($post->image);
        $post->delete();
        session()->flash('message', 'Post deleted successfully!');
    }

    public function render()
    {
        $user = auth()->user();

        $posts = $user->isAdmin()
            ? Post::latest()->paginate(10)
            : $user->posts()->latest()->paginate(10);

        return view('livewire.posts.index', compact('posts'));
    }
}
