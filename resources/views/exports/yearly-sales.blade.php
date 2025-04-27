<table align="left">
    <thead>
        <tr style="background-color: #1d4ed8; color: white;">
            <th style="background-color: #7F64A1; color: white;">Month</th>
            <th style="background-color: #7F64A1; color: white;">Total Orders</th>
            <th style="background-color: #7F64A1; color: white;">Total Sales</th>
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
                <td align="left" width="40">{{ $data['month'] }}</td> <!-- Display month name -->
                <td align="left" width="40">{{ $data['total_orders'] }}</td>
                <td align="left" width="40">₱{{ number_format($data['total_sales'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="subtotal-row">
            <td align="right">Yearly Total</td>
            <td align="left">{{ $totalYearlyOrders }}</td>
            <td align="left">₱{{ number_format($totalYearlySales, 2) }}</td>
        </tr>
    </tfoot>
</table>
