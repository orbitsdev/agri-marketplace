<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationDropdown extends Component
{

    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();

        if ($user) {
            $this->notifications = $user->notifications()
                ->latest()
                ->take(20)
                ->get();

            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();

        if ($user) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification && is_null($notification->read_at)) {
                $notification->markAsRead();
                $this->loadNotifications();
            }
        }
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->loadNotifications();
        }
    }
    public function render()
    {
        return view('livewire.notification.notification-dropdown');
    }
}
