<x-layout title="Sales Report">

<div class="max-w-6xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">Sales Report</h2>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('reports.sales') }}" class="bg-white p-4 rounded shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <input type="date" name="start"
                   value="{{ $start ?? request('start') }}"
                   class="border p-2 rounded w-full" />

            <input type="date" name="end"
                   value="{{ $end ?? request('end') }}"
                   class="border p-2 rounded w-full" />

            <div class="flex items-center">
                <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">Generate</button>
            </div>

            <div class="flex gap-2 justify-end items-center">
                <a href="{{ route('reports.sales.excel', request()->all()) }}"
                   class="bg-green-600 text-white px-3 py-2 rounded">
                    Export Excel
                </a>

                <a href="{{ route('reports.sales.pdf', request()->all()) }}"
                   class="bg-gray-700 text-white px-3 py-2 rounded">
                    Export PDF
                </a>
            </div>

        </div>
    </form>

    {{-- Summary Section --}}
    <div class="mb-4">
        <p class="font-semibold">Range: {{ $start }} → {{ $end }}</p>

        <p class="font-semibold">
            Total Sales: <span class="text-blue-600">GHS {{ number_format($total, 2) }}</span> |
            Total Paid: <span class="text-green-600">GHS {{ number_format($paid, 2) }}</span> |
            Outstanding: <span class="text-red-600">GHS {{ number_format($outstanding, 2) }}</span>
        </p>
    </div>

    {{-- Chart --}}
    @if(!empty($chartLabels) && !empty($chartSales))
        <div class="bg-white p-4 rounded shadow mb-6">
            <div class="h-72">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    @endif

    {{-- Sales Table --}}
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Invoice</th>
                    <th class="p-3 text-left">Cashier</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">Paid</th>
                    <th class="p-3 text-left">Method</th>
                    <th class="p-3 text-left">Date</th>
                </tr>
            </thead>

            <tbody>
            @forelse($sales as $sale)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3">{{ $sale->invoice_no }}</td>
                    <td class="p-3">{{ $sale->user->name ?? '-' }}</td>
                    <td class="p-3">GHS {{ number_format($sale->total, 2) }}</td>
                    <td class="p-3">GHS {{ number_format($sale->paid, 2) }}</td>
                    <td class="p-3 capitalize">{{ $sale->payment_method }}</td>
                    <td class="p-3">
                        {{ $sale->created_at->format('M d, Y - h:i A') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-3 text-center text-gray-500">
                        No sales found for the selected date range
                    </td>
                </tr>
            @endforelse
            </tbody>

            @if($sales->count() > 0)
            <tfoot class="bg-gray-100 font-bold">
                <tr>
                    <td class="p-3">TOTAL</td>
                    <td class="p-3"></td>
                    <td class="p-3">GHS {{ number_format($total, 2) }}</td>
                    <td class="p-3">GHS {{ number_format($paid, 2) }}</td>
                    <td class="p-3"></td>
                    <td class="p-3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

</div>

{{-- Chart.js --}}
@if(!empty($chartLabels) && !empty($chartSales))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($chartLabels) !!};
const salesData = {!! json_encode($chartSales) !!};
const paidData = {!! json_encode($chartPaid ?? []) !!};

const ctx = document.getElementById('salesChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Total Sales (GHS)',
                data: salesData,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            },
            @if(!empty($chartPaid))
            {
                label: 'Amount Paid (GHS)',
                data: paidData,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.15)',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            }
            @endif
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endif

</x-layout>
