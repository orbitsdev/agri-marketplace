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
                ->color('primary'),

            Stat::make('Out of Stock', $outOfStock)
                ->description('Stock needs replenishment')
                ->descriptionIcon($outOfStockIcon)
                ->chart([$outOfStock])
                ->color($outOfStock > 0 ? 'danger' : 'success'),

                  Stat::make('Total Orders', $totalOrders)
                ->description("Pending: $pending | Completed: $completedOrders")
                // ->color('primary'),

            // Stat::make('Low Stock', $lowStock)
            //     ->description('Products below alert level')
            //     ->descriptionIcon($lowStockIcon)
            //     ->chart([$lowStock, $totalProducts - $lowStock])
            //     ->color($lowStock > 0 ? 'warning' : 'success'),

            // Stat::make('Available Products', $totalAvailable)
            //     ->description('Ready for sale')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->chart([$totalAvailable, $totalProducts - $totalAvailable])
            //     ->color('success'),

            // Stat::make('Sold Products', $totalSold)
            //     ->description('Sold so far')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->chart([$totalSold])
            //     ->color('info'),

            // Stat::make('Published Products', $publishedProducts)
            //     ->description('Visible to customers')
            //     ->descriptionIcon($publishedProductsIcon)
            //     ->chart([$publishedProducts, $totalProducts - $publishedProducts])
            //     ->color($publishedProducts > 0 ? 'success' : 'warning'),
        ];
    }
}
