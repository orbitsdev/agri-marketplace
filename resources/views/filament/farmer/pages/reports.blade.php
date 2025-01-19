<x-filament-panels::page>
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-gray-700">Available Reports</h2>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('reports.monthly-sales') }}" class="text-sm text-blue-600 underline hover:text-blue-800">
                    Monthly Sales Report
                </a>
            </li>
            <li>
                <a href="{{ route('reports.yearly-sales') }}" class="text-sm text-blue-600 underline hover:text-blue-800">
                    Yearly Sales Report
                </a>
            </li>
            <li>
                <a href="{{ route('reports.total-products') }}" class="text-sm text-blue-600 underline hover:text-blue-800">
                    Total Products Report
                </a>
            </li>
            <li>
                <a href="{{ route('reports.out-of-stock-products') }}" class="text-sm text-blue-600 underline hover:text-blue-800">
                    Out of Stock Products Report
                </a>
            </li>
            <li>
                <a href="{{ route('reports.total-orders') }}" class="text-sm text-blue-600 underline hover:text-blue-800">
                    Total Orders Report
                </a>
            </li>
            <li>
                <a href="{{ route('reports.orders-by-status') }}" class="text-sm text-blue-600 underline hover:text-blue-800">
                    Orders by Status Report
                </a>
            </li>
        </ul>
    </div>
</x-filament-panels::page>
