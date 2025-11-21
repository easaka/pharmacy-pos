<x-layout title="Add Stock Entry">

<div class="max-w-3xl mx-auto py-6">

    <!-- Page Title -->
    <h2 class="text-3xl font-semibold text-gray-800 mb-6">
        Add Stock Entry
    </h2>

    <!-- Form Card -->
    <form action="{{ route('stock.store') }}" method="POST"
          class="bg-white border border-gray-100 shadow-lg rounded-xl p-6 space-y-6">
        @csrf

        <!-- Product -->
        <div>
            <label class="text-sm font-medium text-gray-700">Product</label>
            <select name="product_id" required
                class="w-full mt-1 p-3 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white">
                <option value="">-- Select Product --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                @endforeach
            </select>
        </div>

        <!-- Type & Quantity -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-700">Stock Type</label>
                <select name="type" required
                    class="w-full mt-1 p-3 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white">
                    <option value="in">Stock In</option>
                    <option value="out">Stock Out</option>
                    <option value="adjustment">Adjustment</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Quantity</label>
                <input name="quantity" type="number" min="1" required
                    class="w-full mt-1 p-3 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white" />
            </div>
        </div>

        <!-- Batch -->
        <div>
            <label class="text-sm font-medium text-gray-700">Batch Number (optional)</label>
            <input name="batch_number"
                class="w-full mt-1 p-3 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white" />
        </div>

        <!-- Expiry -->
        <div>
            <label class="text-sm font-medium text-gray-700">Expiry Date (optional)</label>
            <input name="expiry_date" type="date"
                class="w-full mt-1 p-3 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white" />
        </div>

        <!-- Note -->
        <div>
            <label class="text-sm font-medium text-gray-700">Note</label>
            <textarea name="note" rows="3"
                class="w-full mt-1 p-3 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition">
                Save Entry
            </button>
        </div>
    </form>

</div>

</x-layout>
