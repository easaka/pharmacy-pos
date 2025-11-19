<x-layout title="Profit & Loss">
<div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Profit & Loss</h2>

    <form method="GET" action="{{ route('reports.profitloss') }}" class="bg-white p-4 rounded shadow mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="date" name="start" value="{{ $start ?? request('start') }}" class="border p-2 rounded" />
        <input type="date" name="end" value="{{ $end ?? request('end') }}" class="border p-2 rounded" />
        <div class="flex items-center">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Generate</button>
        </div>
        <div class="flex gap-2 justify-end">
            <a href="{{ route('reports.profitloss.export.excel', request()->all()) }}" class="bg-green-600 text-white px-3 py-2 rounded">Export Excel</a>
            <a href="{{ route('reports.profitloss.export.pdf', request()->all()) }}" class="bg-gray-700 text-white px-3 py-2 rounded">Export PDF</a>
        </div>
    </form>

    <div class="mb-4">
        <p>Total Revenue: GHS {{ number_format($totalRevenue,2) }}</p>
        <p>Total Cost: GHS {{ number_format($totalCost,2) }}</p>
        <p class="font-bold">Profit: GHS {{ number_format($profit,2) }}</p>
    </div>

    <div class="bg-white p-4 rounded shadow mb-6">
        <canvas id="profitChart" height="120"></canvas>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50"><tr><th class="p-3">Invoice</th><th class="p-3">Product</th><th class="p-3">Qty</th><th class="p-3">Revenue</th><th class="p-3">Cost</th><th class="p-3">Profit</th></tr></thead>
            <tbody>
            @foreach($items as $it)
                <tr class="border-t">
                    <td class="p-3">{{ $it->sale->invoice_no ?? '' }}</td>
                    <td class="p-3">{{ $it->product->name ?? '' }}</td>
                    <td class="p-3">{{ $it->quantity }}</td>
                    <td class="p-3">{{ number_format($it->subtotal,2) }}</td>
                    <td class="p-3">{{ number_format(($it->product->cost_price ?? 0) * $it->quantity,2) }}</td>
                    <td class="p-3">{{ number_format($it->subtotal - (($it->product->cost_price ?? 0) * $it->quantity),2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labelsPL = {!! json_encode($labels) !!};
const rev = {!! json_encode($revenueData) !!};
const cost = {!! json_encode($costData) !!};

new Chart(document.getElementById('profitChart'), {
    type: 'line',
    data: {
        labels: labelsPL,
        datasets: [
            { label: 'Revenue', data: rev, tension:0.2 },
            { label: 'Cost', data: cost, tension:0.2 }
        ]
    },
    options: { responsive:true, maintainAspectRatio:false }
});
</script>
</x-layout>
