<x-layout title="Stock Entries">

<div class="max-w-6xl mx-auto py-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">
            Stock Entries
        </h2>

        <a href="{{ route('stock.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium shadow transition">
            + Add Entry
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-gray-200 shadow-lg rounded-xl overflow-hidden">

        <table class="min-w-full border-collapse">
            <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                <tr>
                    <th class="p-3 text-left">Product</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Quantity</th>
                    <th class="p-3 text-left">Batch</th>
                    <th class="p-3 text-left">Expiry Date</th>
                    <th class="p-3 text-left">Date Added</th>
                </tr>
            </thead>

            <tbody class="text-gray-800 text-sm">
                @forelse($entries as $e)
                    <tr class="border-t hover:bg-gray-50 transition">
                        
                        <!-- Product -->
                        <td class="p-3 font-medium text-gray-900">
                            {{ $e->product->name ?? '—' }}
                        </td>

                        <!-- Type Badge -->
                        <td class="p-3">
                            @php
                                $color = match($e->type) {
                                    'in' => 'bg-green-100 text-green-700',
                                    'out' => 'bg-red-100 text-red-700',
                                    'adjustment' => 'bg-yellow-100 text-yellow-700',
                                    default => 'bg-gray-100 text-gray-600'
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $color }}">
                                {{ ucfirst($e->type) }}
                            </span>
                        </td>

                        <!-- Quantity -->
                        <td class="p-3 font-semibold">
                            {{ $e->quantity }}
                        </td>

                        <!-- Batch -->
                        <td class="p-3 text-gray-600">
                            {{ $e->batch_number ?: '—' }}
                        </td>

                        <!-- Expiry -->
                        <td class="p-3 text-red-600">
                            {{ $e->expiry_date ?: '—' }}
                        </td>

                        <!-- Date Added -->
                        <td class="p-3 text-gray-500">
                            {{ $e->created_at->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            No stock entries available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $entries->links() }}
    </div>

</div>

</x-layout>
