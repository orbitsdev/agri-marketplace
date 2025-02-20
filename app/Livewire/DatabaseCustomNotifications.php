<?php

namespace App\Livewire;

use Filament\Livewire\DatabaseNotifications;
use Livewire\Attributes\On;
use Livewire\Component;

class DatabaseCustomNotifications extends DatabaseNotifications
{


    public function markAllNotificationsAsRead(): void
    {
        dd("Got it");
        $this->getUnreadNotificationsQuery()->update(['read_at' => now()]);
    }
}
