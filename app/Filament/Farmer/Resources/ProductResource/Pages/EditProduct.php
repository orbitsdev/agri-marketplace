<?php

namespace App\Filament\Farmer\Resources\ProductResource\Pages;

use App\Filament\Farmer\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

protected function mutateFormDataBeforeFill(array $data): array
{   
    // $data['farmer_id'] = auth()->id();
    
    return $data;
}


protected function mutateFormDataBeforeSave(array $data): array
{
    // $data['farmer_id'] = auth()->id();
 
    return $data;
}
}
