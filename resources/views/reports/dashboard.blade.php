<x-layout title="Reports Dashboard">

<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Financial Reports Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('reports.sales') }}" class="p-6 bg-white shadow rounded-lg hover:bg-blue-50">
            <h3 class="font-bold text-xl mb-2">Sales Report</h3>
            <p class="text-gray-600">Daily, Monthly & Custom Range Sales</p>
        </a>

        <a href="{{ route('reports.purchases') }}" class="p-6 bg-white shadow rounded-lg hover:bg-green-50">
            <h3 class="font-bold text-xl mb-2">Purchases Report</h3>
            <p class="text-gray-600">Track cost of goods purchased</p>
        </a>

        <a href="{{ route('reports.fastmoving') }}" class="p-6 bg-white shadow rounded-lg hover:bg-yellow-50">
            <h3 class="font-bold text-xl mb-2">Fast Moving Products</h3>
            <p class="text-gray-600">Top selling items</p>
        </a>

        <a href="{{ route('reports.profitloss') }}" class="p-6 bg-white shadow rounded-lg hover:bg-purple-50">
            <h3 class="font-bold text-xl mb-2">Profit & Loss</h3>
            <p class="text-gray-600">Revenue, COGS, Net Profit</p>
        </a>

        <a href="{{ route('reports.stockvaluation') }}" class="p-6 bg-white shadow rounded-lg hover:bg-indigo-50">
            <h3 class="font-bold text-xl mb-2">Stock Valuation</h3>
            <p class="text-gray-600">Inventory worth by cost price</p>
        </a>

        <a href="{{ route('reports.expiry') }}" class="p-6 bg-white shadow rounded-lg hover:bg-red-50">
            <h3 class="font-bold text-xl mb-2">Expiry Report</h3>
            <p class="text-gray-600">Items expiring soon</p>
        </a>

        <a href="{{ route('reports.lowstock') }}" class="p-6 bg-white shadow rounded-lg hover:bg-gray-100">
            <h3 class="font-bold text-xl mb-2">Low Stock</h3>
            <p class="text-gray-600">Products needing restocking</p>
        </a>

    </div>
</div>

</x-layout>
