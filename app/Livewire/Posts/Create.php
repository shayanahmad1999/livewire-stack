<?php

namespace App\Livewire\Posts;

use App\Livewire\Forms\Posts\PostForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;
    public PostForm $form;

    public function store()
    {
        $this->form->store();
        return $this->redirect(route('posts.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.posts.create');
    }
}
