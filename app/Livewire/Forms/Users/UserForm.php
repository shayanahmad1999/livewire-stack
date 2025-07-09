<?php

namespace App\Livewire\Forms\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function store(): void
    {
        $validated = $this->validate();
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        $this->reset();
        session()->flash('message', 'User created successfully!');
        redirect()->route('users.index');
    }

    public function update()
    {
        $rules = $this->rules();
        $rules['email'] = ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user->id),];
        $rules['password'] = ['nullable', 'string', 'confirmed', Rules\Password::defaults(),];

        $validated = $this->validate($rules);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $this->user->update($validated);
        $this->reset();
        session()->flash('message', 'User updated successfully!');
    }
}
