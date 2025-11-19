<x-layout title="Point of Sale">
<div class="max-w-7xl mx-auto">

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Products list -->
        <div class="md:col-span-2 bg-white p-4 rounded shadow h-[70vh] overflow-auto">
            <h3 class="font-bold mb-3">Products</h3>

            <div class="grid grid-cols-3 gap-3">
                @foreach($products as $p)
                    <button
                        class="product-card border p-3 text-left rounded hover:bg-gray-50"
                        data-id="{{ $p->id }}"
                        data-name="{{ $p->name }}"
                        data-price="{{ $p->selling_price }}"
                        >
                        <div class="font-semibold">{{ $p->name }}</div>
                        <div class="text-sm text-gray-600">{{ $p->sku }}</div>
                        <div class="text-sm mt-2">GHS {{ number_format($p->selling_price,2) }}</div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Cart -->
        <div class="bg-white p-4 rounded shadow h-[70vh] flex flex-col">
            <h3 class="font-bold mb-3">Cart</h3>

            <form id="checkoutForm" method="POST" action="{{ route('pos.checkout') }}">
                @csrf
                <div id="cartItems" class="space-y-2 overflow-auto max-h-[45vh]">
                    <p class="text-gray-500">No items yet. Click a product to add.</p>
                </div>

                <div class="mt-4">
                    <label class="block mb-1">Payment Method</label>
                    <select name="payment_method" required class="w-full p-2 border rounded mb-2">
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="card">Card</option>
                    </select>

                    <label class="block mb-1">Amount Paid</label>
                    <input type="number" name="paid" step="0.01" class="w-full p-2 border rounded mb-2" required />

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Complete Sale</button>
                </div>
            </form>

            <div class="mt-4 text-sm text-gray-600">
                Tip: scan barcode into search field or click product cards.
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const cart = [];
    const cartItemsEl = document.getElementById('cartItems');
    const form = document.getElementById('checkoutForm');

    function renderCart(){
        cartItemsEl.innerHTML = '';
        if(cart.length === 0){
            cartItemsEl.innerHTML = '<p class="text-gray-500">No items yet. Click a product to add.</p>';
            return;
        }
        cart.forEach((it, idx) => {
            const row = document.createElement('div');
            row.className = 'flex justify-between items-center border rounded p-2';
            row.innerHTML = `
                <div>
                    <div class="font-semibold">${it.name}</div>
                    <div class="text-sm text-gray-600">GHS ${parseFloat(it.price).toFixed(2)}</div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="number" min="1" value="${it.qty}" data-idx="${idx}" class="qty-input w-16 p-1 border rounded" />
                    <div class="w-24 text-right">GHS ${(it.qty*it.price).toFixed(2)}</div>
                    <button data-idx="${idx}" class="remove-btn text-red-600 ml-2">Remove</button>
                </div>
                <input type="hidden" name="items[${idx}][product_id]" value="${it.id}" />
                <input type="hidden" name="items[${idx}][quantity]" value="${it.qty}" />
            `;
            cartItemsEl.appendChild(row);
        });

        // rebind events
        document.querySelectorAll('.qty-input').forEach(inp=>{
            inp.onchange = function(){
                const idx = this.dataset.idx;
                cart[idx].qty = parseInt(this.value) || 1;
                updateHiddenInputs();
                renderCart();
            };
        });
        document.querySelectorAll('.remove-btn').forEach(btn=>{
            btn.onclick = function(){
                cart.splice(this.dataset.idx,1);
                updateHiddenInputs();
                renderCart();
            };
        });
    }

    function updateHiddenInputs(){
        // Rebuild hidden inputs so form fields names are sequential
        // We'll re-render each cart item so inputs include correct indexes.
    }

    document.querySelectorAll('.product-card').forEach(btn=>{
        btn.onclick = function(){
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = parseFloat(this.dataset.price);
            const existing = cart.find(c => c.id == id);
            if(existing) existing.qty += 1;
            else cart.push({ id, name, price, qty: 1 });
            renderCart();
        };
    });

    // on submit: ensure hidden inputs align with indexes
    form.addEventListener('submit', function(e){
        if(cart.length === 0){
            e.preventDefault();
            alert('Cart is empty');
            return;
        }

        // Remove any existing hidden inputs first
        document.querySelectorAll('input[name^="items"]').forEach(el => el.remove());

        cart.forEach((it, idx)=>{
            const pid = document.createElement('input');
            pid.type = 'hidden';
            pid.name = `items[${idx}][product_id]`;
            pid.value = it.id;
            form.appendChild(pid);

            const qty = document.createElement('input');
            qty.type = 'hidden';
            qty.name = `items[${idx}][quantity]`;
            qty.value = it.qty;
            form.appendChild(qty);
        });
    });

});
</script>
</x-layout>