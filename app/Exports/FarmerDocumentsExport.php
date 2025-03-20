<?php

namespace App\Exports;

use App\Models\Farmer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FarmerDocumentsExport implements FromView, ShouldAutoSize
{
    protected $farmerId;

    public function __construct($farmerId)
    {
        $this->farmerId = $farmerId;
    }

    public function view(): View
    {
        $farmer = Farmer::with(['user', 'documents.media']) // Load user and documents
            ->where('id', $this->farmerId)
            ->firstOrFail();

            dd($farmer);
        return view('exports.farmer-documents', [
            'farmer' => $farmer,
        ]);
    }
}
