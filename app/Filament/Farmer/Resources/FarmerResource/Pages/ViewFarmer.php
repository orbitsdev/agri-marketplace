<?php

namespace App\Filament\Farmer\Resources\FarmerResource\Pages;

use App\Filament\Farmer\Resources\FarmerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFarmer extends ViewRecord
{
    protected static string $resource = FarmerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
