<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\OutOfStockExport;
use App\Exports\TotalOrdersExport;
use App\Exports\YearlySalesExport;
use App\Exports\MonthlySalesExport;
use App\Exports\TotalProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersByStatusExport;

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


}
