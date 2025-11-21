<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pharmacy Inventory Management System' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- NAVBAR -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Brand -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <i class="fas fa-pills text-blue-600 text-2xl"></i>
                        <span class="text-xl font-bold text-gray-900">Pharmacy IMS</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('dashboard') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('products.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-box mr-1"></i> Products
                    </a>
                    <a href="{{ route('stock.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stock.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-warehouse mr-1"></i> Stock
                    </a>
                    <a href="{{ route('pos.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('pos.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-cash-register mr-1"></i> POS
                    </a>
                    <a href="{{ route('reports.dashboard') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-chart-line mr-1"></i> Reports
                    </a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('users.index') }}" 
                               class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('users.*') || request()->routeIs('permissions.*') ? 'bg-red-100 text-red-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-users mr-1"></i> Users
                            </a>
                            <a href="{{ route('categories.index') }}" 
                               class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('categories.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-folder mr-1"></i> Categories
                            </a>
                            <a href="{{ route('permissions.roles') }}" 
                               class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('permissions.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-key mr-1"></i> Permissions
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Right side: Notifications and User -->
                <div class="flex items-center space-x-4">
                    @auth
                    <!-- Notifications -->
                    <div x-data="{ open: false, data: { low_stock_count: 0, expiring_count: 0, low_stock_items: [], expiring_items: [] } }" 
                         x-init="
                            fetch('/notifications')
                                .then(res => res.json())
                                .then(json => data = json)
                                .catch(() => {})
                         "
                         class="relative" 
                         @click.away="open = false">
                        <button @click="open = !open" 
                                class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-colors">
                            <i class="fas fa-bell text-xl"></i>
                            <span x-show="(data.low_stock_count + data.expiring_count) > 0" 
                                  x-cloak
                                  class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                <span x-text="data.low_stock_count + data.expiring_count"></span>
                            </span>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" 
                             x-cloak
                             x-transition
                             class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-96 overflow-y-auto">
                            <div class="p-4 border-b border-gray-200">
                                <h4 class="font-bold text-gray-900">Notifications</h4>
                            </div>
                            <div class="p-4">
                                <template x-if="data.low_stock_count == 0 && data.expiring_count == 0">
                                    <p class="text-sm text-gray-500 text-center py-4">No alerts at this time.</p>
                                </template>

                                <template x-if="data.low_stock_count > 0">
                                    <div class="mb-4">
                                        <h5 class="font-semibold text-red-600 mb-2 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-2"></i> Low Stock (<span x-text="data.low_stock_count"></span>)
                                        </h5>
                                        <div class="space-y-2">
                                            <template x-for="item in data.low_stock_items" :key="item.id">
                                                <div class="text-sm p-2 bg-red-50 rounded border-l-4 border-red-500">
                                                    <p class="font-medium text-gray-900" x-text="item.name"></p>
                                                    <p class="text-gray-600">Stock: <span x-text="item.current_stock"></span> / Reorder: <span x-text="item.reorder_level"></span></p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="data.expiring_count > 0">
                                    <div>
                                        <h5 class="font-semibold text-orange-600 mb-2 flex items-center">
                                            <i class="fas fa-calendar-times mr-2"></i> Expiring Soon (<span x-text="data.expiring_count"></span>)
                                        </h5>
                                        <div class="space-y-2">
                                            <template x-for="item in data.expiring_items" :key="item.id">
                                                <div class="text-sm p-2 bg-orange-50 rounded border-l-4 border-orange-500">
                                                    <p class="font-medium text-gray-900" x-text="item.product ? item.product.name : 'Unknown Product'"></p>
                                                    <p class="text-gray-600">Expires: <span x-text="item.expiry_date"></span></p>
                                                    <p class="text-gray-600" x-show="item.batch_number">Batch: <span x-text="item.batch_number"></span></p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-3 border-l border-gray-300 pl-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
                    <div>
                        <p class="font-medium mb-2">Please correct the following errors:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-center text-sm text-gray-600">
                &copy; {{ date('Y') }} Pharmacy Inventory Management System. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>