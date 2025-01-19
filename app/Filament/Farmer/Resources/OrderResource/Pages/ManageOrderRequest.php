<?php

namespace App\Filament\Farmer\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminForm;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Farmer\Resources\OrderResource;

class ManageOrderRequest extends EditRecord 
{   

    use WireUiActions;
    protected static string $resource = OrderResource::class;
     
    protected function getSavedNotification(): ?Notification
    {
        return null;
        // return Notification::make()
        //     ->success()
        //     ->title('User updated')
        //     ->body('The user has been saved successfully.');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Start a database transaction
        DB::beginTransaction();
    
        try {
            // Validate stock before proceeding
            if (isset($data['status']) && $data['status'] === 'Confirmed') {
                // Get all the items in the order
                $orderItems = $record->items;
    
                foreach ($orderItems as $item) {
                    $product = $item->product;
    
                    // Check if the product exists and has sufficient quantity
                    if (!$product || $product->quantity < $item->quantity) {
                        // Show error notification
                        Notification::make()
                            ->title('Stock Error')
                            ->body("Insufficient stock for product: {$item->product_name}")
                            ->danger()
                            ->send();
    
                        // Rollback the transaction and return early
                        DB::rollBack();
                        return $record;
                    }
                }
            }
    
            // Update the order record
            $record->update($data);
    
            // Deduct product quantities after the update
            if ($data['status'] === 'Confirmed') {
                foreach ($record->items as $item) {
                    $product = $item->product;
                    $product->quantity -= $item->quantity;
                    $product->save();
                }
            }
    
            // Commit the transaction
            DB::commit();
    
            // Show success notification
            Notification::make()
                ->title('Success')
                ->body('Order updated successfully!')
                ->success()
                ->send();
    
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
    
            // Show error notification
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
    
            // Optionally log the error
            logger()->error($e->getMessage(), ['trace' => $e->getTrace()]);
        }
    
        return $record;
    }
    
    


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected ?string $heading = 'Manage Order';
    public function form(Form $form): Form
{
    return $form
        ->schema(AdminForm::manageOrderRequestForm());
}
}
