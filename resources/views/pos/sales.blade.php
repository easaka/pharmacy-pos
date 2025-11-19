<x-layout title="Sales History">
<div class="max-w-6xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">Sales History</h2>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3">Invoice</th>
                <th class="p-3">User</th>
                <th class="p-3">Total</th>
                <th class="p-3">Paid</th>
                <th class="p-3">Date</th>
                <th class="p-3">Items</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sales as $s)
                <tr class="border-t">
                    <td class="p-3">{{ $s->invoice_no }}</td>
                    <td class="p-3">{{ $s->user->name ?? '-' }}</td>
                    <td class="p-3">{{ number_format($s->total,2) }}</td>
                    <td class="p-3">{{ number_format($s->paid,2) }}</td>
                    <td class="p-3">{{ $s->created_at->toDateString() }}</td>
                    <td class="p-3">
                        <ul>
                        @foreach($s->items as $it)
                            <li>{{ $it->product->name ?? '—' }} x {{ $it->quantity }}</li>
                        @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $sales->links() }}</div>
</div>
</x-layout>
