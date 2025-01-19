<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class TotalOrdersExport implements FromView
{
    public function view(): View
    {
        $farmerId = Auth::user()->farmer->id; // Get the authenticated farmer's ID

        // Retrieve total orders for the farmer
        $orders = Order::where('farmer_id', $farmerId)
        ->notProcessing()
            ->with(['buyer']) // Include buyer details
            ->get();

        return view('exports.total-orders', [
            'orders' => $orders,
        ]);
    }
}
