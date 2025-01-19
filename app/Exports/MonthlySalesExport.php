<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MonthlySalesExport implements FromView
{
    protected $month;
    protected $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month ?? now()->month; // Default to current month
        $this->year = $year ?? now()->year;   // Default to current year
    }

    public function view(): View
    {
        // Fetch completed orders for the given month and year
        $orders = Order::where('status', Order::COMPLETED)
            ->whereMonth('order_date', $this->month)
            ->whereYear('order_date', $this->year)
            ->with('buyer') // Eager load the buyer relationship
            ->latest()
            ->get();

        return view('exports.monthly-sales', [
            'orders' => $orders,
            'month' => $this->month,
            'year' => $this->year,
        ]);
    }
}
