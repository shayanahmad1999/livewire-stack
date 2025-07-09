<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Url(history: true)]
    public $search = '';

    public function changeRole($id, $newRole)
    {
        $user = User::findOrFail($id);
        $user->role = $newRole;
        $user->save();
        $this->reset();
        session()->flash('message', 'Role of ' . $user->name . ' to ' . $newRole . ' Change Successfully');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        session()->flash('message', 'User deleted successfully!');
    }

    public function render()
    {
        $users = User::query()
            ->whereNot('id', auth()->user()->id)
            ->when(
                $this->search,
                fn($query) =>
                $query->where(
                    fn($q) =>
                    $q->where('name', 'LIKE', "%{$this->search}%")
                        ->orWhere('email', 'LIKE', "%{$this->search}%")
                )
            )
            ->latest()->paginate(10);
        return view('livewire.users.index', [
            'users' => $users,
        ]);
    }
}
