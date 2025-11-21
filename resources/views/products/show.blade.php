<x-layout :title="$product->name">

<div class="max-w-5xl mx-auto py-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">
            {{ $product->name }}
        </h2>

        <!-- Stock Badge -->
        <span class="
            px-3 py-1 rounded-full text-sm font-medium
            @if($product->current_stock <= 5)
                bg-red-100 text-red-700
            @elseif($product->current_stock <= 15)
                bg-yellow-100 text-yellow-700
            @else
                bg-green-100 text-green-700
            @endif
        ">
            Stock: {{ $product->current_stock }}
        </span>
    </div>

    <!-- Product Main Card -->
    <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">

        <!-- Basic Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-500">SKU</p>
                <p class="font-medium text-gray-800">{{ $product->sku }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Category</p>
                <p class="font-medium text-gray-800">{{ $product->category->name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Supplier</p>
                <p class="font-medium text-gray-800">{{ $product->supplier->name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Cost Price</p>
                <p class="font-medium text-gray-800">
                    GH₵ {{ number_format($product->cost_price, 2) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Selling Price</p>
                <p class="font-medium text-gray-800">
                    GH₵ {{ number_format($product->selling_price, 2) }}
                </p>
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700">Description</h3>
            <p class="mt-2 text-gray-600 leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </p>
        </div>

        <!-- Stock Entries Table -->
        <h3 class="text-xl font-semibold mt-8 mb-3 text-gray-800">Stock Entries</h3>

        <div class="overflow-hidden border border-gray-200 rounded-lg shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wide">
                    <tr>
                        <th class="p-3">Type</th>
                        <th class="p-3">Quantity</th>
                        <th class="p-3">Batch No.</th>
                        <th class="p-3">Expiry Date</th>
                        <th class="p-3">Date Added</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    @forelse($product->stockEntries as $e)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ ucfirst($e->type) }}</td>
                            <td class="p-3 font-medium">{{ $e->quantity }}</td>
                            <td class="p-3">{{ $e->batch_number }}</td>
                            <td class="p-3 text-red-600">{{ $e->expiry_date }}</td>
                            <td class="p-3 text-gray-500">
                                {{ $e->created_at->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-3 text-center text-gray-500">
                                No stock entries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

</x-layout>
