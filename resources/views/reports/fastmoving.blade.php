<x-layout title="Fast Moving Items">
<div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Top Selling Products</h2>

    <form method="GET" action="{{ route('reports.fastmoving') }}" class="bg-white p-4 rounded shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="date" name="start" value="{{ $start ?? request('start') }}" class="border p-2 rounded" />
            <input type="date" name="end" value="{{ $end ?? request('end') }}" class="border p-2 rounded" />
            <div class="flex items-center">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Generate</button>
            </div>
            <div class="flex gap-2 justify-end">
                {{-- Fix route names --}}
                <a href="{{ route('reports.fastmoving.excel', request()->all()) }}" class="bg-green-600 text-white px-3 py-2 rounded">Export Excel</a>
                <a href="{{ route('reports.fastmoving.pdf', request()->all()) }}" class="bg-gray-700 text-white px-3 py-2 rounded">Export PDF</a>
            </div>
        </div>
    </form>

    @if(!empty($labels) && !empty($data))
    <div class="bg-white p-4 rounded shadow mb-6">
        <canvas id="fastChart" height="120"></canvas>
    </div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">SKU</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Qty Sold</th>
                    <th class="p-3">Revenue (GHS)</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $it)
                <tr class="border-t">
                    <td class="p-3">{{ $it->sku ?? 'N/A' }}</td>
                    <td class="p-3">{{ $it->name }}</td>
                    <td class="p-3">{{ $it->qty_sold }}</td>
                    <td class="p-3">{{ number_format($it->revenue, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-3 text-center text-gray-500">
                        No sales data found for the selected period
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(!empty($labels) && !empty($data))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labelsFast = {!! json_encode($labels) !!};
const dataFast = {!! json_encode($data) !!};

new Chart(document.getElementById('fastChart'), {
    type: 'bar',
    data: {
        labels: labelsFast,
        datasets: [{ 
            label: 'Quantity Sold', 
            data: dataFast, 
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1 
        }]
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