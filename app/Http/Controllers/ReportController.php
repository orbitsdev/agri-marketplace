<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\YearlySalesExport;
use App\Exports\MonthlySalesExport;
use Maatwebsite\Excel\Facades\Excel;

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


}
