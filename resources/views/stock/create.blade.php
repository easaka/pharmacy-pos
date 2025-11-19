<x-layout title="Add Stock Entry">
<div class="max-w-2xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">Add Stock Entry</h2>

    <form action="{{ route('stock.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <label class="block mb-2">Product</label>
        <select name="product_id" required class="w-full p-2 border rounded mb-4">
            <option value="">-- select product --</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
            @endforeach
        </select>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2">Type</label>
                <select name="type" required class="w-full p-2 border rounded mb-4">
                    <option value="in">Stock In</option>
                    <option value="out">Stock Out</option>
                    <option value="adjustment">Adjustment</option>
                </select>
            </div>
            <div>
                <label class="block mb-2">Quantity</label>
                <input name="quantity" type="number" min="1" required class="w-full p-2 border rounded mb-4" />
            </div>
        </div>

        <label class="block mb-2">Batch Number (optional)</label>
        <input name="batch_number" class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2">Expiry Date (optional)</label>
        <input name="expiry_date" type="date" class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2">Note</label>
        <textarea name="note" class="w-full p-2 border rounded mb-4"></textarea>

        <button class="bg-green-600 text-white px-4 py-2 rounded">Save Entry</button>
    </form>

</div>
</x-layout>
