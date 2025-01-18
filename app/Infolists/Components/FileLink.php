<?php

namespace App\Infolists\Components;

use Filament\Infolists\Components\Component;

class FileLink extends Component
{
    protected string $view = 'infolists.components.file-link';

    public static function make(): static
    {
        return app(static::class);
    }
}
