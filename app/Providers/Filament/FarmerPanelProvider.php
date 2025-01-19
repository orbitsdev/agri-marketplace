<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use App\Filament\Farmer\Pages\Reports;
use App\Http\Middleware\EnsureIsFarmer;
use Filament\Navigation\NavigationItem;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Pages\Auth\RegisterFarmer;
use App\Http\Middleware\EnsureFarmerIsApproved;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Farmer\Resources\FarmerResource\Widgets\LatestAdminOrders;

class FarmerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('farmer')
            ->path('farmer')
            ->login()
            ->registration(RegisterFarmer::class)
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Farmer/Resources'), for: 'App\\Filament\\Farmer\\Resources')
            ->discoverPages(in: app_path('Filament/Farmer/Pages'), for: 'App\\Filament\\Farmer\\Pages')
            ->pages([
                Pages\Dashboard::class,

            ])
            ->discoverWidgets(in: app_path('Filament/Farmer/Widgets'), for: 'App\\Filament\\Farmer\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                LatestAdminOrders::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureFarmerIsApproved::class,
                EnsureIsFarmer::class
            ])
            ->navigationItems([
                // NavigationItem::make('Reports')
                // ->url(fn (): string => Reports::getUrl())
                //     ->icon('heroicon-o-arrow-down-tray')
                //     ->group('Reports')
                //     ->sort(3),
                // NavigationItem::make('dashboard')
                //     ->label(fn (): string => __('filament-panels::pages/dashboard.title'))
                //     ->url(fn (): string => Dashboard::getUrl())
                //     ->isActiveWhen(fn () => request()->routeIs('filament.admin.pages.dashboard')),
                // ...
            ])
            ->sidebarCollapsibleOnDesktop()
            
            ;
    }
}
