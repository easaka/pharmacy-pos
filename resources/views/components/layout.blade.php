<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pharmacy Inventory' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">

    <!-- NAVBAR -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-600">Pharmacy IMS</h1>

        <div class="flex items-center gap-4">
    <a href="{{ route('dashboard') }}" class="font-bold text-blue-600">IMS</a>
    <a href="{{ route('products.index') }}" class="text-sm">Products</a>
    <a href="{{ route('stock.index') }}" class="text-sm">Stock</a>
    <a href="{{ route('pos.index') }}" class="text-sm">POS</a>
    <a href="{{ route('pos.sales') }}" class="text-sm">Sales</a>
    <a href="{{ route('reports.dashboard') }}" class="text-sm">Reports</a>
  </div>

        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">
                Logout
            </button>
        </form>
        @endauth
    </nav>

    <!-- PAGE CONTENT -->
    <div class="p-6">
        {{ $slot }}
    </div>

</body>
</html>
