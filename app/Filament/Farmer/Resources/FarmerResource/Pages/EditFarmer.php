<?php

namespace App\Filament\Farmer\Resources\FarmerResource\Pages;

use App\Filament\Farmer\Resources\FarmerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFarmer extends EditRecord
{
    protected static string $resource = FarmerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
