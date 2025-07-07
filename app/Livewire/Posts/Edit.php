<?php

namespace App\Livewire\Posts;

use App\Livewire\Forms\Posts\PostForm;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public PostForm $form;

    public function mount(Post $post)
    {
        $this->form->setPost($post);
    }

    public function update()
    {
        $this->form->update();
        return $this->redirect(route('posts.index'), navigate: true);
    }
    public function render()
    {
        return view('livewire.posts.edit');
    }
}
