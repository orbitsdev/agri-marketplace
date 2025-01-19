<table align="left">
    <thead>
        <tr style="background-color: #1d4ed8; color: white;">
            <th style="background-color: #7F64A1; color: white;">Code</th>
            <th style="background-color: #7F64A1; color: white;">Product Name</th>
            <th style="background-color: #7F64A1; color: white;">Category</th>
            <th style="background-color: #7F64A1; color: white;">Status</th>
            <th style="background-color: #7F64A1; color: white;">Quantity</th>
            <th style="background-color: #7F64A1; color: white;">Price</th>
            <th style="background-color: #7F64A1; color: white;">Alert Level</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td align="left" width="40">{{ $product->code ?? 'N/A' }}</td>
                <td align="left" width="40">{{ $product->product_name ?? 'N/A' }}</td>
                <td align="left" width="40">{{ $product->category->name ?? 'N/A' }}</td>
                <td align="left" width="40">{{ $product->status }}</td>
                <td align="left" width="40">{{ $product->quantity }}</td>
                <td align="left" width="40">₱{{ number_format($product->price, 2) }}</td>
                <td align="left" width="40">{{ $product->alert_level }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
