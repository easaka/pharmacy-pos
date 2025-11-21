<x-layout title="Products">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Products</h1>
                <p class="mt-1 text-gray-600">Manage your inventory products</p>
            </div>
            <div class="flex gap-3">
                @can('manage-products')
                <a href="{{ route('products.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add Product
                </a>
                @endcan
                @can('manage-stock')
                <a href="{{ route('stock.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                    <i class="fas fa-boxes mr-2"></i> Stock Entry
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i> Search Products
                </label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name, SKU, category, or supplier..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i> Category
                </label>
                <select name="category" 
                        id="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort Options -->
            <div>
                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sort mr-1"></i> Sort By
                </label>
                <div class="flex gap-2">
                    <select name="sort_by" 
                            id="sort_by"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="sku" {{ request('sort_by') == 'sku' ? 'selected' : '' }}>SKU</option>
                        <option value="selling_price" {{ request('sort_by') == 'selling_price' ? 'selected' : '' }}>Price</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                    </select>
                    <select name="sort_order" 
                            id="sort_order"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>↑</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>↓</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-4 flex items-end gap-2">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                @if(request()->hasAny(['search', 'category', 'sort_by', 'sort_order']))
                    <a href="{{ route('products.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times mr-2"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        <!-- Results Summary -->
        @if(request()->hasAny(['search', 'category']))
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Found <strong>{{ $products->total() }}</strong> product(s)
                    @if(request('search'))
                        matching "<strong>{{ request('search') }}</strong>"
                    @endif
                </p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $p)
                        @php
                            $stock = $p->currentStock();
                            $isLowStock = $stock <= ($p->reorder_level ?? 0);
                        @endphp
                        <tr class="hover:bg-blue-50 transition-colors {{ $isLowStock ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono font-semibold text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $p->sku ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $p->name }}</div>
                                        @if($p->description)
                                            <div class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($p->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-folder mr-1"></i>{{ $p->category->name }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $p->supplier->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-right">
                                    <span class="text-sm font-bold text-gray-900">GHS {{ number_format($p->selling_price ?? 0, 2) }}</span>
                                    @if($p->cost_price)
                                        <div class="text-xs text-gray-500">Cost: GHS {{ number_format($p->cost_price, 2) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $isLowStock ? 'bg-red-100 text-red-800 border border-red-300' : ($stock == 0 ? 'bg-gray-100 text-gray-800 border border-gray-300' : 'bg-green-100 text-green-800 border border-green-300') }}">
                                        <i class="fas fa-box mr-1"></i>{{ $stock }}
                                        @if($p->reorder_level)
                                            <span class="ml-1 text-xs">/ {{ $p->reorder_level }}</span>
                                        @endif
                                    </span>
                                    @if($isLowStock)
                                        <i class="fas fa-exclamation-circle text-red-500 ml-2" title="Low Stock"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('products.show', $p) }}" 
                                       class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-100 rounded transition-colors" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('manage-products')
                                    <a href="{{ route('products.edit', $p) }}" 
                                       class="p-2 text-yellow-600 hover:text-yellow-900 hover:bg-yellow-100 rounded transition-colors" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $p) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:text-red-900 hover:bg-red-100 rounded transition-colors" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-box-open text-4xl mb-3"></i>
                                    <p class="text-sm font-medium">No products found</p>
                                    <p class="text-xs mt-1">Get started by adding your first product</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
                    </div>
                    <div>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        @elseif($products->total() > 0)
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="text-sm text-gray-600 text-center">
                    Showing {{ $products->total() }} product(s)
                </div>
            </div>
        @endif
    </div>
</x-layout>
