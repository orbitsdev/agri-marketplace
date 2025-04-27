@extends('reports.printable.layout')

@section('content')
<h2>Orders by Status Report</h2>

@php
    $totalAllOrders = 0;
    $totalAllAmount = 0;
@endphp

@foreach ($ordersByStatus as $status => $orders)
    <h3>Status: {{ $status }}</h3>
    
    <table style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th>Order Number</th>
                <th>Buyer Name</th>
                <th>Total</th>
                <th>Order Date</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusTotal = 0;
            @endphp
            @foreach ($orders as $order)
                @php
                    $orderTotal = $order->calculateTotalOrders();
                    $statusTotal += $orderTotal;
                    $totalAllAmount += $orderTotal;
                    $totalAllOrders++;
                @endphp
                <tr>
                    <td>{{ $order->order_number ?? 'N/A' }}</td>
                    <td>{{ $order->buyer->full_name ?? 'N/A' }}</td>
                    <td>₱{{ number_format($orderTotal, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }}</td>
                    <td>{{ $order->remarks ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right;">Status Total ({{ count($orders) }} orders)</th>
                <th>₱{{ number_format($statusTotal, 2) }}</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
@endforeach

<div class="summary">
    <h3>Summary</h3>
    <p><strong>Total Orders:</strong> {{ $totalAllOrders }}</p>
    <p><strong>Total Amount:</strong> ₱{{ number_format($totalAllAmount, 2) }}</p>
    
    <h4>Orders Count by Status:</h4>
    <ul>
        @foreach($ordersByStatus as $status => $orders)
            <li>{{ $status }}: {{ count($orders) }} ({{ round((count($orders) / $totalAllOrders) * 100, 2) }}%)</li>
        @endforeach
    </ul>
</div>
@endsection
