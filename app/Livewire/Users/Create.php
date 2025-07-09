<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\Users\UserForm;
use Livewire\Component;

class Create extends Component
{
    public UserForm $form;

    public function store()
    {
        $this->form->store();
        return $this->redirect(route('users.index'), navigate: true);
    }
    public function render()
    {
        return view('livewire.users.create');
    }
}
