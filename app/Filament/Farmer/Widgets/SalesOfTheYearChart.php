<?php

namespace App\Filament\Farmer\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SalesOfTheYearChart extends ChartWidget
{
    protected static ?string $heading = 'Sales of the Year (Monthly)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $farmerId = Auth::user()->farmer->id;

        // Initialize sales data for each month in the current year
        $monthlySales = [];
        $currentYear = now()->year;

        for ($month = 1; $month <= 12; $month++) {
            // Fetch orders for the current month
            $ordersForMonth = Order::query()
                ->byFarmer($farmerId)
                ->whereMonth('order_date', $month)
                ->whereYear('order_date', $currentYear)
                ->whereIn('status', [Order::COMPLETED])
                // ->whereIn('status', [Order::COMPLETED, Order::OUT_FOR_DELIVERY])
                ->get();

            // Calculate the sales for the month using the `calculateTotalOrders` method
            $salesForMonth = $ordersForMonth->sum(function ($order) {
                return $order->calculateTotalOrders();
            });

            // Add the month's sales to the data
            $monthlySales[date('F', mktime(0, 0, 0, $month, 1))] = $salesForMonth;
        }

        return [
            'labels' => array_keys($monthlySales), // Labels for months
            'datasets' => [
                [
                    'label' => 'Monthly Sales (₱)',
                    'data' => array_values($monthlySales), // Sales data
                    'backgroundColor' => '#10B981', // Tailwind: Green-500
                    'borderColor' => '#6EE7B7', // Tailwind: Green-300
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
