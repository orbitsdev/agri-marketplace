@extends('reports.printable.layout')

@section('content')
<h2>Total Orders Report</h2>

<table>
    <thead>
        <tr>
            <th>Order Number</th>
            <th>Buyer Name</th>
            <th>Status</th>
            <th>Total</th>
            <th>Order Date</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalAmount = 0;
        @endphp
        @foreach ($orders as $order)
            @php
                $orderTotal = $order->calculateTotalOrders();
                $totalAmount += $orderTotal;
            @endphp
            <tr>
                <td>{{ $order->order_number ?? 'N/A' }}</td>
                <td>{{ $order->buyer->full_name ?? 'N/A' }}</td>
                <td>{{ $order->status }}</td>
                <td>₱{{ number_format($orderTotal, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }}</td>
                <td>{{ $order->remarks ?? 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="subtotal-row">
            <td colspan="3" style="text-align: right;">Total</td>
            <td>₱{{ number_format($totalAmount, 2) }}</td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
</table>

<div class="summary">
    <h3>Summary</h3>
    <p><strong>Total Orders:</strong> {{ count($orders) }}</p>
    <p><strong>Total Amount:</strong> ₱{{ number_format($totalAmount, 2) }}</p>
    
    @php
        $statusCounts = $orders->groupBy('status')->map->count();
        $recentOrders = $orders->sortByDesc('order_date')->take(5);
    @endphp
    
    <h4>Orders by Status:</h4>
    <ul>
        @foreach($statusCounts as $status => $count)
            <li>{{ $status }}: {{ $count }}</li>
        @endforeach
    </ul>
    
    <h4>Recent Orders:</h4>
    <ul>
        @foreach($recentOrders as $order)
            <li>{{ $order->order_number }} - {{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }} - ₱{{ number_format($order->calculateTotalOrders(), 2) }}</li>
        @endforeach
    </ul>
</div>
@endsection
