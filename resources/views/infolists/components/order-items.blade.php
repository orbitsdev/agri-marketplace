<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="overflow-x-auto  w-full" >
        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4  ">Image</th>
                    <th class="border border-gray-300 px-4  ">Product Name</th>
                    <th class="border border-gray-300 px-4  ">Description</th>
                    <th class="border border-gray-300 px-4  ">Quantity</th>
                    <th class="border border-gray-300 px-4  ">Price per Unit</th>
                    <th class="border border-gray-300 px-4  ">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($getRecord()->items as $item)
                    <tr>
                        <td class="border border-gray-300 px-4  ">
                            <img src="{{ $item->product->getImage() }}" alt="Product Image" class="h-16 w-16 object-cover">
                        </td>
                        <td class="border border-gray-300 px-4  ">{{ $item->product_name }}</td>
                        <td class="border border-gray-300 px-4  ">{{ $item->short_description }}</td>
                        <td class="border border-gray-300 px-4  ">{{ $item->quantity }}</td>
                        <td class="border border-gray-300 px-4  ">₱{{ number_format($item->price_per_unit, 2) }}</td>
                        <td class="border border-gray-300 px-4  ">{{ $item->formatted_subtotal }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right font-bold px-4  ">Total:</td>
                    <td class="border border-gray-300 px-4   font-bold">
                        ₱{{ number_format($getRecord()->items->sum(fn($item) => $item->subtotal), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</x-dynamic-component>
