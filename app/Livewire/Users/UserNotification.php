<?php

namespace App\Livewire\Users;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserNotification extends Component
{

    public $unread = [];
    public $read = [];
    public $theme = 'dark';

    public function mount()
    {
        $user = Auth::user();
        $this->unread = $user->unreadNotifications;
        $this->read = $user->readNotifications;
    }

    public function toggleTheme()
    {
        $this->theme = $this->theme === 'light' ? 'dark' : 'light';
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification && $notification->read_at === null) {
            $notification->markAsRead();
            $this->refreshNotifications();
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $user = Auth::user();
        $this->unread = $user->unreadNotifications;
        $this->read = $user->readNotifications;
    }

    public function render()
    {
        return view('livewire.users.user-notification');
    }
}
