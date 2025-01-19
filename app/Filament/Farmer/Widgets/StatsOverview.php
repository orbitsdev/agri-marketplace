<?php

namespace App\Filament\Farmer\Widgets;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $farmerId = Auth::user()->farmer->id;

        $totalOrders = Order::query()->byFarmer($farmerId)->totalOrders();
        $completedOrders = Order::query()->byFarmer($farmerId)->completedOrders();
        $pending = Order::query()->byFarmer($farmerId)->pendingOrders();
        $canceled = Order::query()->byFarmer($farmerId)->cancelledOrders();
        $returned = Order::query()->byFarmer($farmerId)->returnedOrders();
        $outForDelivery = Order::query()->byFarmer($farmerId)->OutForDelivery();
        $shipped = Order::query()->byFarmer($farmerId)->Shipped();


        // Retrieve statistics using scopes
        $totalProducts = Product::query()->myProduct($farmerId)->totalProducts();
        $outOfStock = Product::query()->myProduct($farmerId)->outOfStock();
        $lowStock = Product::query()->myProduct($farmerId)->lowStock()->count();
        $totalAvailable = Product::query()->myProduct($farmerId)->totalByStatus(Product::AVAILABLE);
        $totalSold = Product::query()->myProduct($farmerId)->totalByStatus(Product::SOLD);
        $publishedProducts = Product::query()->myProduct($farmerId)->published()->count();


        // Define dynamic icons
        $lowStockIcon = $lowStock > 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up';
        $outOfStockIcon = $outOfStock > 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up';
        $publishedProductsIcon = $publishedProducts > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        return [
            Stat::make('Total Products', $totalProducts)
                ->description("Available: $totalAvailable | Sold: $totalSold")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([$totalAvailable, $totalSold])
                ->color('primary')
                ->extraAttributes([
                    "class" => "cursor-pointer",
                    "wire:click" => "goto('reports.total-products')",
                ])
                ,

            Stat::make('Out of Stock', $outOfStock)
                ->description('Stock needs replenishment')
                ->descriptionIcon($outOfStockIcon)
                ->chart([$outOfStock])
                ->color($outOfStock > 0 ? 'danger' : 'success')
                ->extraAttributes([
                    "class" => "cursor-pointer",
                    "wire:click" => "goto('reports.out-of-stock-products')",
                ])
                ,

                  Stat::make('Total Orders', $totalOrders)
                ->description("Pending: $pending | Completed: $completedOrders |  Cancelled: $canceled | Returned: $returned | Out For Delivery: $outForDelivery | Shipped: $shipped")
                ->extraAttributes([
                    "class" => "cursor-pointer",
                    "wire:click" => "goto('reports.total-orders')",
                ])
                
        ];
    }

    public function goto($routeName)
    {
        return redirect()->route($routeName);
    }
}
