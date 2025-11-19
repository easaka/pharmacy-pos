<x-layout title="Low Stock">
<div class="max-w-6xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">Low Stock Products</h2>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr><th class="p-3">Product</th><th class="p-3">Stock</th><th class="p-3">Reorder Level</th></tr>
            </thead>
            <tbody>
            @foreach($products as $p)
                <tr class="border-t">
                    <td class="p-3">{{ $p->name }}</td>
                    <td class="p-3">{{ $p->current_stock }}</td>
                    <td class="p-3">{{ $p->reorder_level }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-layout>
