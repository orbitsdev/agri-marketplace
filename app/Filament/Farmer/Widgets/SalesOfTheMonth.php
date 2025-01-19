<?php

namespace App\Filament\Farmer\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOfTheMonth extends ChartWidget
{
    protected static ?string $heading = 'Sales of the Month';
    protected static ?int $sort = 2; // Optional: Define the widget's sort order

    protected function getData(): array
    {
        $farmerId = Auth::user()->farmer->id;

        // Get the start and end of the current month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Initialize sales data for each week in the current month
        $weeklySales = [];
        $weekNumber = 1;
        $currentWeekStart = $startOfMonth->copy();
        $currentWeekEnd = $currentWeekStart->copy()->endOfWeek();

        while ($currentWeekStart->lessThanOrEqualTo($endOfMonth)) {
            // Fetch orders for the current week
            $ordersForWeek = Order::query()
                ->byFarmer($farmerId)
                ->whereBetween('order_date', [$currentWeekStart, $currentWeekEnd])
                ->whereIn('status', [Order::COMPLETED, Order::OUT_FOR_DELIVERY])
                ->get();

            // Calculate the sales for the week using the `calculateTotalOrders` method
            $salesForWeek = $ordersForWeek->sum(function ($order) {
                return $order->calculateTotalOrders();
            });

            // Add the week's sales to the data
            $weeklySales["Week $weekNumber"] = $salesForWeek;

            // Move to the next week
            $currentWeekStart = $currentWeekStart->addWeek()->startOfWeek();
            $currentWeekEnd = $currentWeekStart->copy()->endOfWeek();
            $weekNumber++;
        }

        return [
            'labels' => array_keys($weeklySales), // Labels for weeks
            'datasets' => [
                [
                    'label' => 'Weekly Sales (₱)',
                    'data' => array_values($weeklySales), // Sales data
                    'backgroundColor' => '#3B82F6', // Tailwind: Blue-500
                    'borderColor' => '#93C5FD', // Tailwind: Blue-300
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
