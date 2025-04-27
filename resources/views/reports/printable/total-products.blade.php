@extends('reports.printable.layout')

@section('content')
<h2>Total Products Report</h2>

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

    @php
        $totalValue = $products->sum(function($product) {
            return $product->price * $product->quantity;
        });
        $totalQuantity = $products->sum('quantity');
        $activeProducts = $products->where('status', 'active')->count();
        $inactiveProducts = $products->where('status', 'inactive')->count();
    @endphp

    <div style="">
        <p>Total Products: {{ count($products) }}</p>
        <p>Total Inventory Value: ₱{{ number_format($totalValue, 2) }}</p>
        <p>Total Quantity: {{ $totalQuantity }}</p>
        <p>Active Products: {{ $activeProducts }}</p>
        <p>Inactive Products: {{ $inactiveProducts }}</p>
    </div>
</div>
@endsection
