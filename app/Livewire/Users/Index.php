<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $filterRole = '';

    public $selectedUsers = [];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterRole()
    {
        $this->resetPage();
    }

    public function getSelectableUsersProperty()
    {
        return User::where('id', '!=', auth()->id())->pluck('id')->toArray();
    }

    public function toggleSelectAll()
    {
        $this->selectedUsers = count($this->selectedUsers) === count($this->selectableUsers)
            ? []
            : $this->selectableUsers;
    }

    public function deleteBulk()
    {
        User::whereIn('id', $this->selectedUsers)->where('id', '!=', auth()->id())->delete();
        $this->selectedUsers = [];
        session()->flash('message', 'Selected users deleted successfully!');
        $this->resetPage();
    }

    public function changeRoleBulk($role)
    {
        User::whereIn('id', $this->selectedUsers)->where('id', '!=', auth()->id())->update(['role' => $role]);
        $this->selectedUsers = [];
        session()->flash('message', 'Roles updated successfully for selected users!');
        $this->resetPage();
    }

    public function changeRole($id, $role)
    {
        if ($id == auth()->id()) return;
        User::where('id', $id)->update(['role' => $role]);
        session()->flash('message', 'User role updated successfully!');
    }

    public function delete($id)
    {
        if ($id == auth()->id()) return;
        User::findOrFail($id)->delete();
        session()->flash('message', 'User deleted successfully!');
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->when($this->filterRole, fn($q) => $q->where('role', $this->filterRole))
            ->when($this->search, fn($q) => $q->where(
                fn($query) =>
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
            ))
            ->latest()
            ->paginate(10);

        return view('livewire.users.index', [
            'users' => $users,
        ]);
    }
}
