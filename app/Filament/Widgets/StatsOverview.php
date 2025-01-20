<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();

        // Count users by role
        $totalFarmers = User::where('role', 'Farmer')->count();
        $totalBuyers = User::where('role', 'Buyer')->count();

        return [
            Stat::make('Total Users', $totalUsers),
            Stat::make('Total Farmers', $totalFarmers),
            Stat::make('Total Buyers', $totalBuyers),        ];
    }
}
