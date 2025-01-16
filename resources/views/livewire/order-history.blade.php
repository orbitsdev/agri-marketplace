<div>
    <x-buyer-layout>
        <div class="bg-white border rounded-lg">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:pb-24">
                <div class="max-w-xl">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Order History</h1>
                    <p class="mt-2 text-sm text-gray-500">Check the status of recent orders, manage returns, and download invoices.</p>
                </div>

                <div class="mt-16 space-y-20">
                    @forelse ($orders as $order)
                        <div class="mb-6 border rounded-md bg-white shadow-sm">
                            <div class=" border-b px-4 py-6 sm:flex sm:items-center sm:justify-between sm:space-x-6 sm:px-6 lg:space-x-8">
                                <dl class="flex-auto space-y-6 divide-y divide-gray-200 text-sm text-gray-600 sm:grid sm:grid-cols-3 sm:gap-x-6 sm:space-y-0 sm:divide-y-0 lg:w-1/2 lg:flex-none lg:gap-x-8">
                                    <div class="flex justify-between sm:block">
                                        <dt class="font-medium text-gray-900">Date placed</dt>
                                        <dd class="sm:mt-1">
                                            <time>{{ $order->order_date }}</time>
                                        </dd>
                                    </div>
                                    <div class="flex justify-between pt-6 sm:block sm:pt-0">
                                        <dt class="font-medium text-gray-900">Order number</dt>
                                        <dd class="sm:mt-1">{{ $order->order_number }}</dd>
                                    </div>
                                    <div class="flex justify-between pt-6 font-medium text-gray-900 sm:block sm:pt-0">
                                        <dt>Total amount</dt>
                                        <dd class="sm:mt-1">{{ $order->formatted_total }}</dd>
                                    </div>
                                </dl>
                                <div>
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset
                                            @switch($order->status)
                                                @case('Completed')
                                                    bg-green-100 text-green-800 ring-green-600/20
                                                    @break
                                                @case('Cancelled')
                                                    bg-red-100 text-red-800 ring-red-600/10
                                                    @break
                                                @case('Shipped')
                                                    bg-blue-100 text-blue-800 ring-blue-700/10
                                                    @break
                                                @case('Out for Delivery')
                                                    bg-yellow-100 text-yellow-800 ring-yellow-600/20
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-700 ring-gray-500/10
                                            @endswitch
                                    ">
                                    {{ $order->status }}
                                </span>
                                </div>
                            </div>

                            <table class=" w-full text-gray-700 sm:mt-6 ">
                                <thead class="text-left text-sm text-gray-600 border-b border-t ">
                                    <tr>
                                        <th scope="col" class="py-3 px-4 font-medium sm:w-2/5 lg:w-1/3">Product</th>
                                        <th scope="col" class="hidden py-3 px-4 font-medium sm:table-cell">Price</th>
                                        <th scope="col" class="hidden py-3 px-4 font-medium sm:table-cell">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($order->items as $index => $item)
                                        {{-- <tr @class(['bg-white' => $index % 2 === 0, 'bg-gray-50' => $index % 2 !== 0])> --}}
                                        <tr class="bg-white">
                                            <td class="py-6 px-4">
                                                <div class="flex items-center">
                                                    <img src="{{ $item->product->getImage() }}" alt="{{ $item->product_name }}" class="mr-6 h-16 w-16 rounded-lg object-cover">
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                                        <div class="mt-1 text-sm text-gray-600">{{ Str::limit($item->product_description, 50) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="hidden py-6 px-4 sm:table-cell">{{ number_format($item->product_price, 2) }}</td>
                                            <td class="hidden py-6 px-4 sm:table-cell">{{ $item->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @empty
                        <div class="text-center text-gray-500">No orders found.</div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </x-buyer-layout>
</div>
