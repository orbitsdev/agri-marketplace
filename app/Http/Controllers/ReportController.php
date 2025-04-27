<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\OutOfStockExport;
use App\Exports\TotalOrdersExport;
use App\Exports\YearlySalesExport;
use App\Exports\MonthlySalesExport;
use App\Exports\TotalProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersByStatusExport;
use App\Exports\FarmerDocumentsExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function exportMonthlySales(Request $request)
{
    $month = $request->get('month', now()->month); // Default to current month
    $year = $request->get('year', now()->year);    // Default to current year

    $monthName = now()->setMonth($month)->format('F');
    $filename = "Monthly_Sales_{$monthName}_{$year}.xlsx";

    return Excel::download(new MonthlySalesExport($month, $year), $filename);
}

public function exportYearlySales(Request $request)
{
    $year = $request->get('year', now()->year); // Default to the current year

    $filename = "Yearly_Sales_{$year}.xlsx";

    return Excel::download(new YearlySalesExport($year), $filename);
}


public function exportTotalProducts()
{
    $filename = 'Total_Products_' . now()->format('Y-m-d') . '.xlsx';

    return Excel::download(new TotalProductsExport, $filename);
}

public function exportOutOfStockProducts()
{
    $filename = 'Out_Of_Stock_Products_' . now()->format('Y-m-d') . '.xlsx';

    return Excel::download(new OutOfStockExport, $filename);
}

public function exportTotalOrders()
{
    $filename = 'Total_Orders_' . now()->format('Y-m-d') . '.xlsx';

    return Excel::download(new TotalOrdersExport, $filename);
}


public function exportOrdersByStatus()
{
    $filename = 'Orders_By_Status_' . now()->format('Y-m-d') . '.xlsx';

    return Excel::download(new OrdersByStatusExport, $filename);
}


public function exportFarmerDocuments(Farmer $farmer)
{
    $fileName = "{$farmer->user->full_name}_{$farmer->farm_name}_documents.xlsx";
  
    return Excel::download(new FarmerDocumentsExport($farmer->id), $fileName);
}

// Printable Reports

public function printableMonthlySales(Request $request)
{
    $month = $request->get('month', now()->month); // Default to current month
    $year = $request->get('year', now()->year);    // Default to current year

    // Fetch completed orders for the given month and year
    $orders = Order::where('status', Order::COMPLETED)
        ->whereMonth('order_date', $month)
        ->whereYear('order_date', $year)
        ->with('buyer') // Eager load the buyer relationship
        ->latest()
        ->get();

    $monthName = now()->setMonth($month)->format('F');
    
    return view('reports.printable.monthly-sales', [
        'orders' => $orders,
        'month' => $month,
        'year' => $year,
        'title' => 'Monthly Sales Report',
        'subtitle' => $monthName . ' ' . $year
    ]);
}

public function printableYearlySales(Request $request)
{
    $year = $request->get('year', now()->year); // Default to the current year

    // Group orders by month and calculate total sales and total orders
    $monthlySales = Order::where('status', Order::COMPLETED)
        ->whereYear('order_date', $year)
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
    
    return view('reports.printable.yearly-sales', [
        'monthlySales' => $monthlySales,
        'year' => $year,
        'title' => 'Yearly Sales Report',
        'subtitle' => 'For Year ' . $year
    ]);
}

public function printableTotalProducts()
{
    $farmerId = Auth::user()->farmer->id; // Get the authenticated farmer's ID

    // Retrieve product data for the farmer
    $products = Product::myProduct($farmerId)
        ->with('category') // Include category details
        ->get();

    return view('reports.printable.total-products', [
        'products' => $products,
        'title' => 'Total Products Report',
        'subtitle' => 'As of ' . now()->format('F j, Y')
    ]);
}

public function printableOutOfStockProducts()
{
    $farmerId = Auth::user()->farmer->id; // Get the authenticated farmer's ID

    // Retrieve out of stock products for the farmer
    $products = Product::myProduct($farmerId)
        ->where('quantity', 0)
        ->with('category') // Include category details
        ->get();

    return view('reports.printable.out-of-stock-products', [
        'products' => $products,
        'title' => 'Out of Stock Products Report',
        'subtitle' => 'As of ' . now()->format('F j, Y')
    ]);
}

public function printableTotalOrders()
{
    $farmerId = Auth::user()->farmer->id; // Get the authenticated farmer's ID

    // Retrieve total orders for the farmer
    $orders = Order::where('farmer_id', $farmerId)
        ->notProcessing()
        ->with(['buyer']) // Include buyer details
        ->get();

    return view('reports.printable.total-orders', [
        'orders' => $orders,
        'title' => 'Total Orders Report',
        'subtitle' => 'As of ' . now()->format('F j, Y')
    ]);
}

public function printableOrdersByStatus()
{
    $farmerId = Auth::user()->farmer->id; // Get the authenticated farmer's ID

    // Retrieve orders grouped by status
    $ordersByStatus = Order::where('farmer_id', $farmerId)
        ->with(['buyer']) // Include buyer details
        ->orderBy('status') // Order by status
        ->get()
        ->groupBy('status'); // Group by status

    return view('reports.printable.orders-by-status', [
        'ordersByStatus' => $ordersByStatus,
        'title' => 'Orders by Status Report',
        'subtitle' => 'As of ' . now()->format('F j, Y')
    ]);
}

public function printableFarmerDocuments(Farmer $farmer)
{
    return view('reports.printable.farmer-documents', [
        'farmer' => $farmer,
        'title' => 'Farmer Documents Report',
        'subtitle' => $farmer->farm_name
    ]);
}


}
