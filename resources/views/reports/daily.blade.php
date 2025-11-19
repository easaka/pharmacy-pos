<x-layout title="Daily Sales">
<div class="max-w-6xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">Sales for {{ $date }}</h2>
    <p class="mb-4">Total: GHS {{ number_format($total,2) }}</p>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50"><tr><th class="p-3">Invoice</th><th class="p-3">Total</th><th class="p-3">Paid</th><th class="p-3">Date</th></tr></thead>
            <tbody>
            @foreach($sales as $s)
                <tr class="border-t">
                    <td class="p-3">{{ $s->invoice_no }}</td>
                    <td class="p-3">{{ number_format($s->total,2) }}</td>
                    <td class="p-3">{{ number_format($s->paid,2) }}</td>
                    <td class="p-3">{{ $s->created_at->toDateTimeString() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>
</x-layout>
