<?php

namespace App\Filament\Farmer\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Actions;
use Filament\Forms\Form;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Services\TeamSSProgramSmsService;
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
        if ($record->wasChanged('status') && !empty($record->phone)) {
            try {
                $smsService = app(TeamSSProgramSmsService::class);
                $buyerName = $record->buyer->full_name ?? 'Customer';
                $orderNumber = $record->order_number;
                $totalOrder = $record->getFormattedTotalAttribute();
                $phone = $record->phone;
                $message = '';
    
                switch ($record->status) {
                    case Order::PENDING:
                        $message = "Hello $buyerName, we’ve received your order #$orderNumber with a total of $totalOrder. It’s currently pending and will be processed soon. Thank you for ordering from Agri Market!";
                        break;
    
    
                    case Order::CONFIRMED:
                        $message = "Good news, $buyerName! Your order #$orderNumber has been confirmed, and we are preparing it for shipment. The total amount is $totalOrder. Thank you for choosing Agri Market!";
                        break;
    
                    case Order::SHIPPED:
                        $message = "Hi $buyerName, your order #$orderNumber has been shipped! It’s on the way, and you’ll receive it soon. We appreciate your trust in Agri Market.";
                        break;
    
                    case Order::OUT_FOR_DELIVERY:
                        $message = "Hello $buyerName, your order #$orderNumber is out for delivery. Please prepare $totalOrder for payment if needed. Expect it to arrive soon. Thank you for ordering with Agri Market!";
                        break;
    
                    case Order::COMPLETED:
                        $message = "Hi $buyerName, your order #$orderNumber has been successfully delivered. We hope you’re happy with your purchase! Let us know if you need anything else.";
                        break;
    
                    case Order::CANCELLED:
                        $message = "Hi $buyerName, we regret to inform you that your order #$orderNumber has been canceled. If you need further assistance, feel free to contact us.";
                        break;
    
                    case Order::RETURNED:
                        $message = "Hello $buyerName, your return request for order #$orderNumber has been processed. If you have any concerns, please reach out to us.";
                        break;
                }
    
                if (!empty($message)) {
                    $smsService->sendSms($phone, $message);
                }
    
            } catch (\Exception $e) {
                logger()->error('SMS sending failed:', ['error' => $e->getMessage()]);
            }
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
