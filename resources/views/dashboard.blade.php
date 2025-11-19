<x-layout title="Dashboard">

<h2 class="text-2xl font-bold">Welcome, {{ auth()->user()->name }} 👋</h2>

<p class="mt-3 text-gray-700">
    This is your Pharmacy Inventory Management System dashboard.
</p>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

    <a href="{{ route('products.index') }}" class="bg-white shadow p-6 rounded hover:bg-blue-50">
        <h3 class="text-lg font-bold">Products</h3>
        <p class="text-gray-600">Manage all medicines & items</p>
    </a>

    <a href="{{ route('stock.index') }}" class="bg-white shadow p-6 rounded hover:bg-blue-50">
        <h3 class="text-lg font-bold">Stock Control</h3>
        <p class="text-gray-600">Track incoming & outgoing stock</p>
    </a>

    <a href="{{ route('pos.index') }}" class="bg-white shadow p-6 rounded hover:bg-blue-50">
        <h3 class="text-lg font-bold">Sales</h3>
        <p class="text-gray-600">Record transactions and receipts</p>
    </a>

</div>

</x-layout>
