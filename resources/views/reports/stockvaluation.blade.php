<x-layout title="Stock Valuation">
<div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Stock Valuation Report</h2>

    <!-- Export Buttons -->
    <div class="flex justify-end gap-2 mb-6">
        <a href="{{ route('reports.stockvaluation.excel') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center">
            <i class="fas fa-file-excel mr-2"></i>Export Excel
        </a>
        <a href="{{ route('reports.stockvaluation.pdf') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center">
            <i class="fas fa-file-pdf mr-2"></i>Export PDF
        </a>
    </div>

    <!-- Summary Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="text-center">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Total Inventory Value</h3>
            <p class="text-3xl font-bold text-blue-600">GHS {{ number_format($totalValue, 2) }}</p>
            <p class="text-sm text-blue-600 mt-2">Based on current stock levels and cost prices</p>
        </div>
    </div>

    <!-- Chart -->
    @if(!empty($labels) && !empty($data))
    <div class="bg-white p-4 rounded shadow mb-6">
        <canvas id="stockChart" height="120"></canvas>
    </div>
    @endif

    <!-- Products Table -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">SKU</th>
                    <th class="p-3 text-left">Product Name</th>
                    <th class="p-3 text-right">Current Stock</th>
                    <th class="p-3 text-right">Cost Price (GHS)</th>
                    <th class="p-3 text-right">Stock Value (GHS)</th>
                </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3">{{ $product->sku ?? 'N/A' }}</td>
                    <td class="p-3">{{ $product->name }}</td>
                    <td class="p-3 text-right">
                        <span class="inline-block px-2 py-1 rounded text-sm {{ $product->current_stock <= ($product->reorder_level ?? 0) ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $product->current_stock }}
                        </span>
                    </td>
                    <td class="p-3 text-right">{{ number_format($product->cost_price, 2) }}</td>
                    <td class="p-3 text-right font-semibold text-blue-600">
                        GHS {{ number_format($product->stock_value, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-3 text-center text-gray-500">
                        No products found in inventory
                    </td>
                </tr>
            @endforelse
            </tbody>
            @if($products->count() > 0)
            <tfoot class="bg-gray-100 font-bold">
                <tr>
                    <td class="p-3" colspan="3">TOTAL INVENTORY VALUE</td>
                    <td class="p-3 text-right"></td>
                    <td class="p-3 text-right text-blue-600">
                        GHS {{ number_format($totalValue, 2) }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <!-- Summary Stats -->
    @if($products->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm font-semibold text-gray-600">Total Products</h4>
            <p class="text-2xl font-bold text-gray-800">{{ $products->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm font-semibold text-gray-600">Out of Stock</h4>
            <p class="text-2xl font-bold text-red-600">
                {{ $products->where('current_stock', 0)->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm font-semibold text-gray-600">Low Stock</h4>
            <p class="text-2xl font-bold text-orange-600">
                {{ $products->filter(fn($p) => $p->current_stock > 0 && $p->current_stock <= ($p->reorder_level ?? 0))->count() }}
            </p>
        </div>
    </div>
    @endif
</div>

@if(!empty($labels) && !empty($data))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labelsStock = {!! json_encode($labels) !!};
const dataStock = {!! json_encode($data) !!};

new Chart(document.getElementById('stockChart'), {
    type: 'bar',
    data: {
        labels: labelsStock,
        datasets: [{ 
            label: 'Stock Value (GHS)', 
            data: dataStock,
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
                        return 'Value: GHS ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endif
</x-layout>