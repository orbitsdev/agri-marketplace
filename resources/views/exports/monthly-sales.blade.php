<table align="left">
    <thead>
        <tr style="background-color: #1d4ed8; color: white;">
            <th style="background-color: #106c3b; color: white;">ID</th>
            <th style="background-color: #106c3b; color: white;">Order Number</th>
            <th style="background-color: #106c3b; color: white;">Buyer</th>
            <th style="background-color: #106c3b; color: white;">Payment Method</th>
            <th style="background-color: #106c3b; color: white;">Region</th>
            <th style="background-color: #106c3b; color: white;">Status</th>
            <th style="background-color: #106c3b; color: white;">Total</th>
            <th style="background-color: #106c3b; color: white;">Order Date</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSales = 0; // Initialize total sales
        @endphp
        @foreach ($orders as $order)
            @php
                $orderTotal = $order->calculateTotalOrders(); // Calculate total for each order
                $totalSales += $orderTotal; // Add to the total sales
            @endphp
            <tr>
                <td align="left" width="40">{{ $order->id }}</td>
                <td align="left" width="40">{{ $order->order_number }}</td>
                <td align="left" width="40">{{ $order->buyer->full_name ?? 'N/A' }}</td>
                <td align="left" width="40">{{ $order->payment_method ?? 'N/A' }}</td>
                <td align="left" width="40">{{ $order->region ?? 'N/A' }}</td>
                <td align="left" width="40">{{ $order->status }}</td>
                <td align="left" width="40">₱{{ number_format($orderTotal, 2) }}</td>
                <td align="left" width="40">{{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="subtotal-row">
            <td colspan="6" style="text-align: right;">Total Sales</td>
            <td>₱{{ number_format($totalSales, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
