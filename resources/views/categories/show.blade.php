<x-layout title="Category Details">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                <p class="mt-1 text-gray-600">Category details and products</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('categories.edit', $category) }}" 
                   class="px-4 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition-colors shadow-sm">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('categories.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Category Information</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $category->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $category->description ?? 'No description' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Products Count</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $category->products->count() }} products</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $category->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Products in this Category</h2>
            @if($category->products->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($category->products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900 font-mono">{{ $product->sku ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">GHS {{ number_format($product->selling_price ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">No products in this category yet.</p>
            @endif
        </div>
    </div>
</x-layout>
