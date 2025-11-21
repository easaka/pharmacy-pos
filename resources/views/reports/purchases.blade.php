<x-layout title="Purchases Report">
<div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Purchases Report</h2>

    <!-- Date Range Filter -->
    <form method="GET" action="{{ route('reports.purchases') }}" class="bg-white p-4 rounded shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="date" name="start" value="{{ $start ?? request('start') }}" class="border p-2 rounded" />
            <input type="date" name="end" value="{{ $end ?? request('end') }}" class="border p-2 rounded" />
            <div class="flex items-center">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Generate</button>
            </div>
            <div class="flex gap-2 justify-end">
                <a href="{{ route('reports.purchases.pdf', request()->all()) }}" class="bg-gray-700 text-white px-3 py-2 rounded flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </a>
            </div>
        </div>
    </form>

    <!-- Summary -->
    <div class="mb-4">
        <p class="font-semibold">Range: {{ $start }} → {{ $end }}</p>
        <p>Total Purchases: GHS {{ number_format($total, 2) }}</p>
    </div>

    <!-- Chart -->
    @if(!empty($labels) && !empty($data))
    <div class="bg-white p-4 rounded shadow mb-6">
        <canvas id="purchasesChart" height="100"></canvas>
    </div>
    @endif

    <!-- Purchases Table -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Purchase #</th>
                    <th class="p-3 text-left">Supplier</th>
                    <th class="p-3 text-left">Contact</th>
                    <th class="p-3 text-right">Total Amount</th>
                    <th class="p-3 text-left">Purchase Date</th>
                    <th class="p-3 text-left">Items</th>
                </tr>
            </thead>
            <tbody>
            @forelse($purchases as $purchase)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-mono">#{{ $purchase->id }}</td>
                    <td class="p-3">{{ $purchase->supplier_name ?? 'No Supplier' }}</td>
                    <td class="p-3 text-sm text-gray-600">{{ $purchase->supplier_contact ?? 'N/A' }}</td>
                    <td class="p-3 text-right font-semibold">GHS {{ number_format($purchase->total, 2) }}</td>
                    <td class="p-3">{{ $purchase->purchase_date ? $purchase->purchase_date->format('M j, Y') : $purchase->created_at->format('M j, Y') }}</td>
                    <td class="p-3">
                        @if($purchase->items && $purchase->items->count() > 0)
                            <div class="text-sm text-gray-600">
                                {{ $purchase->items->count() }} item(s)
                            </div>
                        @else
                            <span class="text-gray-400">No items</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-3 text-center text-gray-500">
                        No purchases found for the selected period
                    </td>
                </tr>
            @endforelse
            </tbody>
            @if($purchases->count() > 0)
            <tfoot class="bg-gray-100 font-bold">
                <tr>
                    <td class="p-3" colspan="3">TOTAL</td>
                    <td class="p-3 text-right">GHS {{ number_format($total, 2) }}</td>
                    <td class="p-3" colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <!-- Quick Stats -->
    @if($purchases->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm font-semibold text-gray-600">Total Purchases</h4>
            <p class="text-2xl font-bold text-gray-800">{{ $purchases->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm font-semibold text-gray-600">Average Purchase</h4>
            <p class="text-2xl font-bold text-blue-600">
                GHS {{ number_format($purchases->avg('total') ?? 0, 2) }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm font-semibold text-gray-600">Largest Purchase</h4>
            <p class="text-2xl font-bold text-green-600">
                GHS {{ number_format($purchases->max('total') ?? 0, 2) }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm font-semibold text-gray-600">Smallest Purchase</h4>
            <p class="text-2xl font-bold text-orange-600">
                GHS {{ number_format($purchases->min('total') ?? 0, 2) }}
            </p>
        </div>
    </div>
    @endif
</div>

@if(!empty($labels) && !empty($data))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($labels) !!};
const data = {!! json_encode($data) !!};

const ctx = document.getElementById('purchasesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Daily Purchases (GHS)',
            data: data,
            backgroundColor: 'rgba(139, 69, 19, 0.5)',
            borderColor: 'rgb(139, 69, 19)',
            borderWidth: 1
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'GHS ' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Amount: GHS ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endif
</x-layout>