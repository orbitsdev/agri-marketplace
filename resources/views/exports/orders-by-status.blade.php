@foreach ($ordersByStatus as $status => $orders)
    <h3 style="color: #1d4ed8;">Status: {{ $status }}</h3>
    <table align="left" style="margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #7F64A1; color: white;">
                <th style="background-color: #7F64A1; color: white;">Order Number</th>
                <th style="background-color: #7F64A1; color: white;">Buyer Name</th>
                <th style="background-color: #7F64A1; color: white;">Total</th>
                <th style="background-color: #7F64A1; color: white;">Order Date</th>
                <th style="background-color: #7F64A1; color: white;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td align="left" width="40">{{ $order->order_number ?? 'N/A' }}</td>
                    <td align="left" width="40">{{ $order->buyer->full_name ?? 'N/A' }}</td>
                    <td align="left" width="40">₱{{ number_format($order->total, 2) }}</td>
                    <td align="left" width="40">{{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }}</td>
                    <td align="left" width="40">{{ $order->remarks ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
