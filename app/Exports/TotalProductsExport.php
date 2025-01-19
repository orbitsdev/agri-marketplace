<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class TotalProductsExport implements FromView
{
    public function view(): View
    {
        $farmerId = Auth::user()->farmer->id; // Get the authenticated farmer's ID

        // Retrieve product data for the farmer
        $products = Product::myProduct($farmerId)
            ->with('category') // Include category details
            ->get();

        return view('exports.total-products', [
            'products' => $products,
        ]);
    }
}
