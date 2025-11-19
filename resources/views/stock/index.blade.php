<x-layout title="Stock Entries">
<div class="max-w-6xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Stock Entries</h2>
        <a href="{{ route('stock.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Add Entry</a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Qty</th>
                <th class="p-3 text-left">Batch</th>
                <th class="p-3 text-left">Expiry</th>
                <th class="p-3 text-left">Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($entries as $e)
                <tr class="border-t">
                    <td class="p-3">{{ $e->product->name ?? '—' }}</td>
                    <td class="p-3">{{ ucfirst($e->type) }}</td>
                    <td class="p-3">{{ $e->quantity }}</td>
                    <td class="p-3">{{ $e->batch_number }}</td>
                    <td class="p-3">{{ $e->expiry_date }}</td>
                    <td class="p-3">{{ $e->created_at->toDateString() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $entries->links() }}
    </div>
</div>
</x-layout>
