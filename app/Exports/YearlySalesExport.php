<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class YearlySalesExport implements FromView
{
    protected $year;

    public function __construct($year = null)
    {
        $this->year = $year ?? now()->year; // Default to the current year
    }

    public function view(): View
    {
        // Group orders by month and calculate total sales and total orders
        $monthlySales = Order::where('status', Order::COMPLETED)
        ->whereYear('order_date', $this->year)
        ->select(
            DB::raw("MONTH(order_date) as month_number"), // Group by raw month number
            DB::raw("MONTHNAME(order_date) as month"),    // Use month name for display
            DB::raw("COUNT(*) as total_orders"),          // Count total orders
            DB::raw("SUM(total) as total_sales")          // Sum total sales
        )
        ->groupBy(DB::raw("MONTH(order_date), MONTHNAME(order_date)")) // Fix the GROUP BY clause
        ->orderBy(DB::raw("MONTH(order_date)")) // Order by raw month number
        ->get()
        ->keyBy('month_number') // Key by the raw month number for consistent indexing
        ->toArray();
    


        return view('exports.yearly-sales', [
            'monthlySales' => $monthlySales,
            'year' => $this->year,
        ]);
    }
}
