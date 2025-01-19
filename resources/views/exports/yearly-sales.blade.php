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
        <tr style="background-color: #1d4ed8; color: white;">
            <th align="right" style="background-color: #FFCC99;">Yearly Total</th>
            <th align="left" style="background-color: #FFCC99;">{{ $totalYearlyOrders }}</th>
            <th align="left" style="background-color: #FFCC99;">₱{{ number_format($totalYearlySales, 2) }}</th>
        </tr>
    </tfoot>
</table>
