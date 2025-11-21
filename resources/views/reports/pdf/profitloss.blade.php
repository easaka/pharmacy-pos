<x-layout title="Profit & Loss Report">
<div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Profit & Loss Report</h2>

    <form method="GET" action="{{ route('reports.profitloss') }}" class="bg-white p-4 rounded shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="date" name="start" value="{{ $start ?? request('start') }}" class="border p-2 rounded" />
            <input type="date" name="end" value="{{ $end ?? request('end') }}" class="border p-2 rounded" />
            <div class="flex items-center">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Generate</button>
            </div>
            <div class="flex gap-2 justify-end">
                {{-- Fix route names --}}
                <a href="{{ route('reports.profitloss.excel', request()->all()) }}" class="bg-green-600 text-white px-3 py-2 rounded">Export Excel</a>
                <a href="{{ route('reports.profitloss.pdf', request()->all()) }}" class="bg-gray-700 text-white px-3 py-2 rounded">Export PDF</a>
            </div>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-green-800">Total Revenue</h3>
            <p class="text-2xl font-bold text-green-600">GHS {{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-red-800">Total Cost</h3>
            <p class="text-2xl font-bold text-red-600">GHS {{ number_format($totalCost, 2) }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-blue-800">Net Profit</h3>
            <p class="text-2xl font-bold text-blue-600">GHS {{ number_format($profit, 2) }}</p>
        </div>
    </div>

    <!-- Chart -->
    @if(!empty($labels) && !empty($rev) && !empty($cost))
    <div class="bg-white p-4 rounded shadow mb-6">
        <canvas id="profitChart" height="120"></canvas>
    </div>
    @endif

    <!-- Detailed Items -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Invoice</th>
                    <th class="p-3">Product</th>
                    <th class="p-3">Qty</th>
                    <th class="p-3">Unit Price</th>
                    <th class="p-3">Revenue</th>
                    <th class="p-3">Cost</th>
                    <th class="p-3">Profit</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr class="border-t">
                    <td class="p-3">{{ $item->sale->invoice_no ?? 'N/A' }}</td>
                    <td class="p-3">{{ $item->product->name ?? 'N/A' }}</td>
                    <td class="p-3">{{ $item->quantity }}</td>
                    <td class="p-3">GHS {{ number_format($item->unit_price, 2) }}</td>
                    <td class="p-3">GHS {{ number_format($item->subtotal, 2) }}</td>
                    <td class="p-3">GHS {{ number_format(($item->product->cost_price ?? 0) * $item->quantity, 2) }}</td>
                    <td class="p-3 font-semibold {{ ($item->subtotal - (($item->product->cost_price ?? 0) * $item->quantity)) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        GHS {{ number_format($item->subtotal - (($item->product->cost_price ?? 0) * $item->quantity), 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-3 text-center text-gray-500">
                        No sales data found for the selected period
                    </td>
                </tr>
            @endforelse
            </tbody>
            @if($items->count() > 0)
            <tfoot class="bg-gray-100 font-bold">
                <tr>
                    <td class="p-3" colspan="4">TOTAL</td>
                    <td class="p-3">GHS {{ number_format($totalRevenue, 2) }}</td>
                    <td class="p-3">GHS {{ number_format($totalCost, 2) }}</td>
                    <td class="p-3 {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        GHS {{ number_format($profit, 2) }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@if(!empty($labels) && !empty($rev) && !empty($cost))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($labels) !!};
const revenueData = {!! json_encode($rev) !!};
const costData = {!! json_encode($cost) !!};

new Chart(document.getElementById('profitChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Revenue (GHS)',
                data: revenueData,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.2,
                borderWidth: 2
            },
            {
                label: 'Cost (GHS)',
                data: costData,
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                fill: true,
                tension: 0.2,
                borderWidth: 2
            }
        ]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endif
</x-layout>