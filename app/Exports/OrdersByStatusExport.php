<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class OrdersByStatusExport implements FromView
{
    public function view(): View
    {
        $farmerId = Auth::user()->farmer->id; // Get the authenticated farmer's ID

        // Retrieve orders grouped by status
        $ordersByStatus = Order::where('farmer_id', $farmerId)
            ->with(['buyer']) // Include buyer details
            ->orderBy('status') // Order by status
            ->get()
            ->groupBy('status'); // Group by status

        return view('exports.orders-by-status', [
            'ordersByStatus' => $ordersByStatus,
        ]);
    }
}
