<div>
    <x-buyer-layout>
        
        <div class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
            <div>

              <p class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Order Summary</p>
              <p class="mt-2 text-base text-gray-500">Please finalize your order below. Here’s a summary of your purchase.</p>
              

            </div>

            <div class="mt-8"></div>
            @forelse ($groupedProcessing as $farmerId => $farmerOrders)
            @php
                $farmerName = $farmerOrders->first()->farmer->farm_name ?? 'Unknown Farmer';
            @endphp
        
            <div class="mb-6 bg-white rounded">
                <ul>
                    @forelse ($farmerOrders as $order)
                 
                        @php
                            $orderTotal = $order->items->sum(function ($item) {
                                return $item->product->price * $item->quantity;
                            });
                        @endphp
                        <div class="mx-auto bg-white shadow-md rounded-lg p-6 mb-8 relative">
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                            @switch($order->status)
                                @case('Pending')
                                    bg-yellow-100 text-yellow-800
                                    @break
                                @case('Processing')
                                    bg-blue-100 text-blue-700
                                    @break
                                @case('Confirmed')
                                    bg-green-100 text-green-700
                                    @break
                                @case('Shipped')
                                    bg-indigo-100 text-indigo-700
                                    @break
                                @case('Out for delivery')
                                    bg-purple-100 text-purple-700
                                    @break
                                @case('Completed')
                                    bg-gray-100 text-gray-600
                                    @break
                                @case('Cancelled')
                                    bg-red-100 text-red-700
                                    @break
                                @default
                                    bg-gray-100 text-gray-600
                            @endswitch">
                            {{ $order->status }}
                        </span>
                            <div class="flex justify-end absolute top-2 right-2">
                                {{ ($this->removeOrderAction)(['record' => $order->id]) }}

                            </div>
                            <div class="flex justify-between items-center mb-6 mt-4">
                                <div>
                                    <h2 class="text-xl font-semibold">Order ID: {{$order->order_number}}</h2>
                                    <p class="text-sm text-gray-600">Order date: {{$order->order_date}}</p>
                                </div>
                                <div class="flex items-center space-x-4 bg-gray-50 border rounded-lg p-3">
                                    <div>
                                        <span class="block text-sm font-semibold text-gray-800">Farm Owner</span>
                                        <span class="block text-sm text-gray-600">{{ $farmerName }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-6">
                                <p class="text-green-600 font-semibold">Estimated Delivery: Guaranteed within 7 days</p>
                            </div>
                            <div class="border-t pt-4">
                                @forelse ($order->items as $item)
                                    <div class="space-y-4">
                                        <!-- Product -->
                                        <div class="flex justify-between items-center">
                                            <div class="flex space-x-4">
                                                <img src="{{$item->product->getImage()}}" alt="Product Image" class="w-16 h-16 rounded-lg object-cover">
                                                <div>
                                                    <h3 class="font-semibold text-gray-800">{{$item->product->product_name}}</h3>
                                                    <p class="text-sm text-gray-600">
                                                        {{ Str::limit($item->product->description, 100) }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-gray-800">Php {{ number_format($item->product->price, 2) }}</p>
                                                <p class="text-sm text-gray-600">Qty: {{$item->quantity}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                            <div class="relative border-t pt-4">
                               
                                <div class="flex justify-end ">
                                    {{ ($this->editAddress)(['record' => $order->id]) }}
                                </div>
                            <div class=" flex justify-between relative ">
                                
                                 
                                <div class="">
                                    <h4 class="font-semibold text-gray-800">Payment</h4>
                                    @if($order->pament_method)
                                    <p class="text-sm text-gray-600">{{$order->pament_method }} </p>
                                    @else
                                    <p class="text-sm  text-red-700 "> None </p>
                                    @endif
                                </div>
                                <div class="relative">
                                    
                                    <h4 class="font-semibold text-gray-800 ">Delivery Address</h4>
                                    @if($order->hasCompleteLocation())
                                    <p class="text-sm text-gray-600">
                                        {{$order->getFormattedAddressAttribute()}}
                                    </p>
                                    @else
                                    <p class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium   text-red-700">
                                       No Address was found
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                            <div class="border-t pt-4 mt-6 flex justify-end">
                                <h4 class="text-lg font-semibold text-gray-800">Total: Php{{ number_format($orderTotal, 2) }}</h4>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No orders found.</p>
                    @endforelse
                </ul>
            </div>
        @empty
            <p class="text-center text-gray-500">No pending orders found.</p>
        @endforelse
        
        </div>

    </x-buyer-layout>
    <x-filament-actions::modals />
    {{-- Because she competes with no one, no one can compete with her. --}}
</div>
