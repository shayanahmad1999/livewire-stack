<?php

namespace App\Livewire\Forms\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PostForm extends Form
{
    public ?Post $post;

    public $title = '';
    public $image;
    public $content = '';

    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'required|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
        ];
    }

    public function setPost(Post $post)
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
    }

    public function store()
    {
        $data = $this->validate();
        $data['slug'] = Str()->slug($data['title']);
        if ($this->image) {
            $data['image'] = $this->image->store('posts', 'public');
        }
        auth()->user()->posts()->create($data);
        $this->reset();
        session()->flash('message', 'Post created successfully!');
    }

    public function update()
    {
        $rules = $this->rules();
        $rules['image'] = 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:2048';
        $data = $this->validate($rules);

        $data['slug'] = Str()->slug($data['title']);
        $data['image'] = $this->post->image;
        if ($this->image) {
            Storage::disk('public')->delete($this->post->image);
            $data['image'] = $this->image->store('posts', 'public');
        }
        $this->post->update($data);
        $this->reset();
        session()->flash('message', 'Post updated successfully!');
    }
}
