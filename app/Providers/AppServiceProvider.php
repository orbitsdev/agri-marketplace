<?php

namespace App\Providers;

use Filament\Support\Assets\Css;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\View\Components\Modal;
use Filament\Support\Colors\Color;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Model::unguard();

        FilamentColor::register([
            'primary' => "#00993c",

            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            // 'primary' => Color::Amber,
            'success' => Color::Green,
            'warning' => Color::Amber,

        ]);
        Modal::closedByClickingAway(false);
        FilamentAsset::register([
            Css::make('custom-stylesheet', __DIR__ . '/../../resources/css/custom.css'),
        ]);

        FilamentColor::register([
            'primary' => "#048E5C",

        ]);
    }
}
