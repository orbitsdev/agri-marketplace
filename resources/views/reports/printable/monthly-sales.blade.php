@extends('reports.printable.layout')

@section('content')
<h2>Monthly Sales Report - {{ \Carbon\Carbon::createFromDate(null, $month, 1)->format('F Y') }}</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Order Number</th>
            <th>Buyer</th>
            <th>Payment Method</th>
            <th>Region</th>
            <th>Status</th>
            <th>Total</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSales = 0; // Initialize total sales
        @endphp
        @foreach ($orders as $order)
            @php
                // Use the total attribute if calculateTotalOrders() is not working properly
                $orderTotal = $order->total ?? $order->calculateTotalOrders(); 
                $totalSales += $orderTotal; // Add to the total sales
            @endphp
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->buyer->full_name ?? 'N/A' }}</td>
                <td>{{ $order->payment_method ?? 'N/A' }}</td>
                <td>{{ $order->region ?? 'N/A' }}</td>
                <td>{{ $order->status }}</td>
                <td>₱{{ number_format($orderTotal, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }}</td>
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

<div class="summary">
    <h3>Summary</h3>
    <p><strong>Total Orders:</strong> {{ count($orders) }}</p>
    <p><strong>Total Sales:</strong> ₱{{ number_format($totalSales, 2) }}</p>
    <p><strong>Month:</strong> {{ \Carbon\Carbon::createFromDate(null, $month, 1)->format('F') }}</p>
    <p><strong>Year:</strong> {{ $year }}</p>
</div>
@endsection
