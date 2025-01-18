<?php

namespace App\Filament\Farmer\Resources\OrderResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Farmer\Resources\OrderResource;

class CreateOrder extends CreateRecord
{   
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
{   
    $data['farmer_id'] = Auth::user()->farmer->id;
    // dd($data);
    return $data;
}
    protected static string $resource = OrderResource::class;
}
