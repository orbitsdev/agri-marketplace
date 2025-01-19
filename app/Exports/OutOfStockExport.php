<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class OutOfStockExport implements FromView
{
    public function view(): View
    {
        $farmerId = Auth::user()->farmer->id; // Get the authenticated farmer's ID

        // Retrieve out-of-stock products for the farmer
        $outOfStockProducts = Product::myProduct($farmerId)
            ->where('quantity', '<=', 0) // Products with quantity <= 0
            ->with('category') // Include category details
            ->get();

        return view('exports.out-of-stock-products', [
            'products' => $outOfStockProducts,
        ]);
    }
}
