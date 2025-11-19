<x-layout title="Expiry Soon">
<div class="max-w-6xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">Products expiring within {{ $days }} days</h2>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr><th class="p-3">Product</th><th class="p-3">Batch</th><th class="p-3">Expiry</th><th class="p-3">Qty</th></tr>
            </thead>
            <tbody>
            @foreach($entries as $e)
                <tr class="border-t">
                    <td class="p-3">{{ $e->product->name ?? '—' }}</td>
                    <td class="p-3">{{ $e->batch_number }}</td>
                    <td class="p-3">{{ $e->expiry_date }}</td>
                    <td class="p-3">{{ $e->quantity }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>
</x-layout>
