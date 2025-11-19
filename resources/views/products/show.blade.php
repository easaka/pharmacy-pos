<x-layout :title="$product->name">
<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">{{ $product->name }}</h2>
        <span class="text-gray-600">Stock: {{ $product->current_stock }}</span>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <p><strong>SKU:</strong> {{ $product->sku }}</p>
        <p><strong>Category:</strong> {{ $product->category->name ?? '-' }}</p>
        <p><strong>Supplier:</strong> {{ $product->supplier->name ?? '-' }}</p>
        <p><strong>Cost:</strong> {{ number_format($product->cost_price,2) }}</p>
        <p><strong>Selling:</strong> {{ number_format($product->selling_price,2) }}</p>
        <p class="mt-4"><strong>Description:</strong><br /> {!! nl2br(e($product->description)) !!}</p>

        <h3 class="text-lg font-bold mt-6">Stock Entries</h3>
        <table class="w-full mt-2">
            <thead class="bg-gray-50">
                <tr><th class="p-2">Type</th><th class="p-2">Qty</th><th class="p-2">Batch</th><th class="p-2">Expiry</th><th class="p-2">Date</th></tr>
            </thead>
            <tbody>
                @foreach($product->stockEntries as $e)
                    <tr class="border-t">
                        <td class="p-2">{{ $e->type }}</td>
                        <td class="p-2">{{ $e->quantity }}</td>
                        <td class="p-2">{{ $e->batch_number }}</td>
                        <td class="p-2">{{ $e->expiry_date }}</td>
                        <td class="p-2">{{ $e->created_at->toDateTimeString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
</x-layout>
