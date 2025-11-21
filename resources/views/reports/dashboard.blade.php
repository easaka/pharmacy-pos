<x-layout title="Reports Dashboard">

<div class="max-w-7xl mx-auto py-8">

    <!-- Page Title -->
    <h1 class="text-4xl font-semibold text-gray-800 mb-8">
        Financial Reports Dashboard
    </h1>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Sales Report -->
        <a href="{{ route('reports.sales') }}"
           class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg hover:-translate-y-1 hover:bg-blue-50 transition duration-200">
            <h3 class="font-semibold text-xl text-gray-800">Sales Report</h3>
            <p class="text-gray-600 mt-2">Daily, Monthly & Custom Range Sales</p>
        </a>

        <!-- Purchases -->
        <a href="{{ route('reports.purchases') }}"
           class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg hover:-translate-y-1 hover:bg-green-50 transition duration-200">
            <h3 class="font-semibold text-xl text-gray-800">Purchases Report</h3>
            <p class="text-gray-600 mt-2">Track cost of goods purchased</p>
        </a>

        <!-- Fast Moving -->
        <a href="{{ route('reports.fastmoving') }}"
           class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg hover:-translate-y-1 hover:bg-yellow-50 transition duration-200">
            <h3 class="font-semibold text-xl text-gray-800">Fast Moving Products</h3>
            <p class="text-gray-600 mt-2">Top selling items</p>
        </a>

        <!-- Profit & Loss -->
        <a href="{{ route('reports.profitloss') }}"
           class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg hover:-translate-y-1 hover:bg-purple-50 transition duration-200">
            <h3 class="font-semibold text-xl text-gray-800">Profit & Loss</h3>
            <p class="text-gray-600 mt-2">Revenue, COGS, Net Profit</p>
        </a>

        <!-- Stock Valuation -->
        <a href="{{ route('reports.stockvaluation') }}"
           class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg hover:-translate-y-1 hover:bg-indigo-50 transition duration-200">
            <h3 class="font-semibold text-xl text-gray-800">Stock Valuation</h3>
            <p class="text-gray-600 mt-2">Inventory worth by cost price</p>
        </a>

        <!-- Expiry Report -->
        <a href="{{ route('reports.expiry') }}"
           class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg hover:-translate-y-1 hover:bg-red-50 transition duration-200">
            <h3 class="font-semibold text-xl text-gray-800">Expiry Report</h3>
            <p class="text-gray-600 mt-2">Items expiring soon</p>
        </a>

        <!-- Low Stock -->
        <a href="{{ route('reports.lowstock') }}"
           class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg hover:-translate-y-1 hover:bg-gray-100 transition duration-200">
            <h3 class="font-semibold text-xl text-gray-800">Low Stock</h3>
            <p class="text-gray-600 mt-2">Products needing restocking</p>
        </a>

    </div>

</div>

</x-layout>
