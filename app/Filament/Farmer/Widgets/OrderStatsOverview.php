<?php

namespace App\Filament\Farmer\Widgets;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrderStatsOverview extends BaseWidget
{   

    protected static ?int $sort = 2;
    protected static bool $shouldRegisterNavigation = false;
    protected function getStats(): array
    {
        $farmerId = Auth::user()->farmer->id;

        // Retrieve statistics using scopes
        $totalOrders = Order::query()->byFarmer($farmerId)->totalOrders();
        $completedOrders = Order::query()->byFarmer($farmerId)->completedOrders();
        $cancelledOrders = Order::query()->byFarmer($farmerId)->cancelledOrders();
        // $pendingOrders = Order::query()->byFarmer($farmerId)->pendingOrders();
        // $returnedOrders = Order::query()->byFarmer($farmerId)->returnedOrders();

        // Define dynamic colors for each stat
        // $completedColor = $completedOrders > 0 ? 'success' : 'warning';
        // $cancelledColor = $cancelledOrders > 0 ? 'danger' : 'success';
        // $pendingColor = $pendingOrders > 0 ? 'warning' : 'success';
        // $returnedColor = $returnedOrders > 0 ? 'info' : 'success';

        return [
            // Stat::make('Total Orders', $totalOrders)
            //     ->description("Completed: $completedOrders | Cancelled: $cancelledOrders")
            //     ->color('primary'),

            // Stat::make('Completed Orders', $completedOrders)
            //     ->description('Orders delivered successfully')
            //     ->color($completedColor),

            // Stat::make('Cancelled Orders', $cancelledOrders)
            //     ->description('Orders cancelled')
            //     ->color($cancelledColor),

            // Stat::make('Pending Orders', $pendingOrders)
            //     ->description('Awaiting confirmation or processing')
            //     ->color($pendingColor),

            // Stat::make('Returned Orders', $returnedOrders)
            //     ->description('Orders returned by buyers')
            //     ->color($returnedColor),
        ];
    }
}
