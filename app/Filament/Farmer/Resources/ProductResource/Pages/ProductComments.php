<?php

namespace App\Filament\Farmer\Resources\ProductResource\Pages;

use App\Filament\Farmer\Resources\ProductResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
class ProductComments extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.farmer.resources.product-resource.pages.product-comments';

    use InteractsWithRecord;

    public function getHeading(): string
    {
        $item = $this->record->product_name;
        return __($item . ' Messages');
    }

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
