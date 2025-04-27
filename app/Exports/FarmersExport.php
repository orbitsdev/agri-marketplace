<?php

namespace App\Exports;

use App\Models\Farmer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FarmersExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $farmers = Farmer::with(['user', 'documents.media'])
            ->whereHas('user')
            ->get();

        return view('exports.farmers', [
            'farmers' => $farmers,
        ]);
    }
}
