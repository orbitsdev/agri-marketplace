@extends('reports.printable.layout')

@section('content')
<h2>Out of Stock Products Report</h2>

<table>
    <thead>
        <tr>
            <th>Code</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Status</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Alert Level</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->code ?? 'N/A'}}</td>
                <td>{{ $product->product_name  ?? 'N/A'}}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ $product->status }}</td>
                <td>{{ $product->quantity }}</td>
                <td>₱{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->alert_level }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="summary">
    <h3>Summary</h3>
    <p><strong>Total Out of Stock Products:</strong> {{ count($products) }}</p>
    
    @php
        $totalValue = $products->sum(function($product) {
            return $product->price * $product->quantity;
        });
        $categoryCounts = $products->groupBy(function($product) {
            return $product->category->name ?? 'Uncategorized';
        })->map->count();
    @endphp
    
    <p><strong>Potential Value (if restocked):</strong> ₱{{ number_format($totalValue, 2) }}</p>
    
    <h4>Out of Stock by Category:</h4>
    <ul>
        @foreach($categoryCounts as $category => $count)
            <li>{{ $category }}: {{ $count }}</li>
        @endforeach
    </ul>
</div>
@endsection
