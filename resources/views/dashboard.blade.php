<x-layout title="Dashboard">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-gray-600">Manage your pharmacy operations from this central dashboard.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('products.index') }}" 
           class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 p-6 border border-gray-200 hover:border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-box text-blue-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Products</h3>
            <p class="text-gray-600 text-sm">Manage all medicines and inventory items</p>
            <div class="mt-4 flex items-center text-blue-600 group-hover:text-blue-700 font-medium">
                <span class="text-sm">View Products</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>

        <a href="{{ route('stock.index') }}" 
           class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 p-6 border border-gray-200 hover:border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-warehouse text-green-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">Stock Control</h3>
            <p class="text-gray-600 text-sm">Track incoming and outgoing stock movements</p>
            <div class="mt-4 flex items-center text-green-600 group-hover:text-green-700 font-medium">
                <span class="text-sm">Manage Stock</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>

        <a href="{{ route('pos.index') }}" 
           class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 p-6 border border-gray-200 hover:border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                    <i class="fas fa-cash-register text-purple-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Point of Sale</h3>
            <p class="text-gray-600 text-sm">Process sales transactions and generate receipts</p>
            <div class="mt-4 flex items-center text-purple-600 group-hover:text-purple-700 font-medium">
                <span class="text-sm">Open POS</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>

        <a href="{{ route('pos.sales') }}" 
           class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 p-6 border border-gray-200 hover:border-indigo-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-colors">
                    <i class="fas fa-receipt text-indigo-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">Sales History</h3>
            <p class="text-gray-600 text-sm">View and manage past sales transactions</p>
            <div class="mt-4 flex items-center text-indigo-600 group-hover:text-indigo-700 font-medium">
                <span class="text-sm">View Sales</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>

        <a href="{{ route('reports.dashboard') }}" 
           class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 p-6 border border-gray-200 hover:border-orange-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-orange-100 rounded-lg group-hover:bg-orange-200 transition-colors">
                    <i class="fas fa-chart-line text-orange-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">Reports</h3>
            <p class="text-gray-600 text-sm">Generate sales, inventory, and financial reports</p>
            <div class="mt-4 flex items-center text-orange-600 group-hover:text-orange-700 font-medium">
                <span class="text-sm">View Reports</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('users.index') }}" 
           class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 p-6 border-2 border-red-200 hover:border-red-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                    <i class="fas fa-users-cog text-red-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-red-600 mb-2">User Management</h3>
            <p class="text-gray-600 text-sm">Manage users, roles, and system permissions</p>
            <div class="mt-4 flex items-center text-red-600 group-hover:text-red-700 font-medium">
                <span class="text-sm">Manage Users</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>
        @endif
    </div>
</x-layout>
