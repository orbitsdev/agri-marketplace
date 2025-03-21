<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
class NotificationDropdown extends Component
{

    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }



    #[On('refreshNotifications')] // ✅ Listens for real-time event
    public function loadNotifications()
    {
        $user = Auth::user();

        if ($user) {
            // Fetch latest 20 notifications from database
            $this->notifications = $user->notifications()
                ->latest()
                ->take(20)
                ->get();

            // Get unread notifications count
            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }


    #[On('markAllAsRead')]
    public function markAllAsReadAction()
    {
        $user = Auth::user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->loadNotifications();
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
