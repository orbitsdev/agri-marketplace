<x-filament-panels::page>
    <div class="space-y-6">
        <h2 class="text-xl font-bold dark:text-gray-200">Reports</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Excel Export Reports -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold dark:text-gray-300 flex items-center">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2 text-primary-500" />
                    Excel Export Reports
                </h3>
                <ul class="space-y-3 pl-7">
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-amber-500 dark:text-amber-400" />
                        <a href="{{ route('reports.monthly-sales') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Monthly Sales Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-amber-500 dark:text-amber-400" />
                        <a href="{{ route('reports.yearly-sales') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Yearly Sales Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-amber-500 dark:text-amber-400" />
                        <a href="{{ route('reports.total-products') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Total Products Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-amber-500 dark:text-amber-400" />
                        <a href="{{ route('reports.out-of-stock-products') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Out of Stock Products Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-amber-500 dark:text-amber-400" />
                        <a href="{{ route('reports.total-orders') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Total Orders Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-amber-500 dark:text-amber-400" />
                        <a href="{{ route('reports.orders-by-status') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Orders by Status Report
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Printable Reports -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold dark:text-gray-300 flex items-center">
                    <x-heroicon-o-printer class="w-5 h-5 mr-2 text-primary-500" />
                    Printable Reports
                </h3>
                <ul class="space-y-3 pl-7">
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-primary-500 dark:text-primary-400" />
                        <a href="{{ route('reports.printable.monthly-sales') }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Monthly Sales Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-primary-500 dark:text-primary-400" />
                        <a href="{{ route('reports.printable.yearly-sales') }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Yearly Sales Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-primary-500 dark:text-primary-400" />
                        <a href="{{ route('reports.printable.total-products') }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Total Products Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-primary-500 dark:text-primary-400" />
                        <a href="{{ route('reports.printable.out-of-stock-products') }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Out of Stock Products Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-primary-500 dark:text-primary-400" />
                        <a href="{{ route('reports.printable.total-orders') }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Total Orders Report
                        </a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-document-text class="w-4 h-4 mr-2 text-primary-500 dark:text-primary-400" />
                        <a href="{{ route('reports.printable.orders-by-status') }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            Orders by Status Report
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-filament-panels::page>
