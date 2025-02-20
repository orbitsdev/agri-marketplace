<?php

namespace App\Filament;

use Closure;
use Illuminate\Support\Arr;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Actions\ActionGroup;
use Filament\Notifications\Notification as BaseNotification;

class Notification extends BaseNotification
{

    protected string $size = 'md'; // Default size

    public function toArray(): array
    {
        return [
            'size' => $this->getSize(), // Ensure size is stored
            ...parent::toArray(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return parent::fromArray($data)->size($data['size']);
    }

    public function size(string $size): static
    {
        $this->size = $size;
        return $this;
    }

    public function getSize(): string
    {
        return $this->size;
    }



    /**
     * @param  array<Action | ActionGroup> | ActionGroup | Closure  $actions
     */
    
}
