<x-layout title="Add Product">
<div class="max-w-3xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">Add Product</h2>

    <form action="{{ route('products.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <label class="block mb-2">SKU</label>
        <input name="sku" class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2">Name</label>
        <input name="name" required class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2">Category</label>
        <select name="category_id" class="w-full p-2 border rounded mb-4">
            <option value="">-- none --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>

        <label class="block mb-2">Supplier</label>
        <select name="supplier_id" class="w-full p-2 border rounded mb-4">
            <option value="">-- none --</option>
            @foreach($suppliers as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2">Cost Price</label>
                <input name="cost_price" class="w-full p-2 border rounded mb-4" />
            </div>
            <div>
                <label class="block mb-2">Selling Price</label>
                <input name="selling_price" class="w-full p-2 border rounded mb-4" />
            </div>
        </div>

        <label class="block mb-2">Reorder Level</label>
        <input name="reorder_level" class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2">Description</label>
        <textarea name="description" class="w-full p-2 border rounded mb-4"></textarea>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Save Product</button>
    </form>

</div>
</x-layout>
