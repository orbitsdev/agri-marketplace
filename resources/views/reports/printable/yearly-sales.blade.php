@extends('reports.printable.layout')

@section('content')
<h2>Yearly Sales Report - {{ $year }}</h2>

<table>
    <thead>
        <tr>
            <th>Month</th>
            <th>Total Orders</th>
            <th>Total Sales</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalYearlySales = 0; // Initialize total yearly sales
            $totalYearlyOrders = 0; // Initialize total yearly orders
        @endphp
        @foreach ($monthlySales as $data)
            @php
                $totalYearlySales += $data['total_sales']; // Accumulate yearly sales
                $totalYearlyOrders += $data['total_orders']; // Accumulate yearly orders
            @endphp
            <tr>
                <td>{{ $data['month'] }}</td> <!-- Display month name -->
                <td>{{ $data['total_orders'] }}</td>
                <td>₱{{ number_format($data['total_sales'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="subtotal-row">
            <td style="text-align: right;">Yearly Total</td>
            <td>{{ $totalYearlyOrders }}</td>
            <td>₱{{ number_format($totalYearlySales, 2) }}</td>
        </tr>
    </tfoot>
</table>

<div class="summary">
    <h3>Summary</h3>
    <p><strong>Total Orders for {{ $year }}:</strong> {{ $totalYearlyOrders }}</p>
    <p><strong>Total Sales for {{ $year }}:</strong> ₱{{ number_format($totalYearlySales, 2) }}</p>
    
    @if(!empty($monthlySales))
        @php
            // Convert to collection if it's an array
            $salesCollection = collect($monthlySales);
            $highestMonth = $salesCollection->sortByDesc('total_sales')->first();
            $lowestMonth = $salesCollection->sortBy('total_sales')->first();
        @endphp
        <p><strong>Highest Sales Month:</strong> {{ $highestMonth['month'] ?? 'N/A' }} (₱{{ number_format($highestMonth['total_sales'] ?? 0, 2) }})</p>
        <p><strong>Lowest Sales Month:</strong> {{ $lowestMonth['month'] ?? 'N/A' }} (₱{{ number_format($lowestMonth['total_sales'] ?? 0, 2) }})</p>
    @endif
</div>
@endsection
