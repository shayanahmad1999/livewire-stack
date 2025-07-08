<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'tailwind';

    #[Url(history: true)]
    public $search = '';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

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

        $posts = ($user->isAdmin()
            ? Post::query()
            : $user->posts()
        )
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('body', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.posts.index', compact('posts'));
    }
}
