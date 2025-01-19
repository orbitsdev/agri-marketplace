<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        {{ $getRecord()->calculateTotalOrders() }}
    </div>
</x-dynamic-component>
