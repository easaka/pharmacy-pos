<x-layout title="Products">
<div class="max-w-7xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Products</h2>
        <div class="flex gap-2">
            <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Product</a>
            <a href="{{ route('stock.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Stock In/Out</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">SKU</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Category</th>
                    <th class="p-3 text-left">Supplier</th>
                    <th class="p-3 text-left">Price</th>
                    <th class="p-3 text-left">Stock</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($products as $p)
                <tr class="border-t">
                    <td class="p-3">{{ $p->sku }}</td>
                    <td class="p-3">{{ $p->name }}</td>
                    <td class="p-3">{{ $p->category->name ?? '-' }}</td>
                    <td class="p-3">{{ $p->supplier->name ?? '-' }}</td>
                    <td class="p-3">{{ number_format($p->selling_price,2) }}</td>
                    <td class="p-3">{{ $p->currentStock() }}</td>
                    <td class="p-3">
                        <a href="{{ route('products.show',$p) }}" class="text-blue-600 mr-2">View</a>
                        <a href="{{ route('products.edit',$p) }}" class="text-yellow-600 mr-2">Edit</a>
                        <form action="{{ route('products.destroy',$p) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete product?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
</x-layout>
