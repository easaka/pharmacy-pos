<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $sale->invoice_no }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .receipt-container { box-shadow: none !important; border: none !important; }
        }
        @page { margin: 0; size: auto; }
    </style>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-md mx-auto">
        <!-- Print Controls -->
        <div class="no-print mb-4 text-center">
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded mr-2">
                🖨️ Print Receipt
            </button>
            <a href="{{ route('pos.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">
                ← New Sale
            </a>
        </div>

        <!-- Receipt -->
        <div class="receipt-container bg-white shadow-lg rounded-lg p-6 border-2 border-gray-300">
            <!-- Header -->
            <div class="text-center border-b-2 border-dashed border-gray-300 pb-4 mb-4">
                <h1 class="text-2xl font-bold text-gray-800">PHARMACY POS</h1>
                <p class="text-gray-600 text-sm">Medical Supplies & Pharmaceuticals</p>
                <p class="text-gray-500 text-xs mt-1">Tel: 024-XXX-XXXX</p>
            </div>

            <!-- Sale Info -->
            <div class="mb-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Invoice No:</span>
                    <span class="font-semibold">{{ $sale->invoice_no }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Date:</span>
                    <span>{{ $sale->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Cashier:</span>
                    <span>{{ $sale->user->name ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Items -->
            <div class="border-b border-dashed border-gray-300 pb-3 mb-3">
                <div class="grid grid-cols-12 gap-2 text-xs font-semibold text-gray-600 mb-2">
                    <div class="col-span-6">ITEM</div>
                    <div class="col-span-2 text-center">QTY</div>
                    <div class="col-span-2 text-right">PRICE</div>
                    <div class="col-span-2 text-right">TOTAL</div>
                </div>
                
                @if($sale->items && count($sale->items) > 0)
                    @foreach($sale->items as $item)
                    <div class="grid grid-cols-12 gap-2 text-sm mb-2">
                        <div class="col-span-6 truncate">{{ $item->product->name ?? 'Product Not Found' }}</div>
                        <div class="col-span-2 text-center">{{ $item->quantity }}</div>
                        <div class="col-span-2 text-right">GHS {{ number_format($item->unit_price, 2) }}</div>
                        <div class="col-span-2 text-right font-semibold">GHS {{ number_format($item->subtotal, 2) }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-4">
                        <p>No items found for this sale</p>
                    </div>
                @endif
            </div>

            <!-- Totals -->
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span>GHS {{ number_format($sale->total, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Amount Paid:</span>
                    <span class="font-semibold">GHS {{ number_format($sale->paid, 2) }}</span>
                </div>
                @if($sale->paid > $sale->total)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Change:</span>
                    <span class="font-semibold">GHS {{ number_format($sale->paid - $sale->total, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Payment Method:</span>
                    <span class="font-semibold capitalize">{{ $sale->payment_method }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center border-t-2 border-dashed border-gray-300 pt-4">
                <p class="text-gray-600 text-sm mb-2">Thank you for your purchase!</p>
                <p class="text-gray-500 text-xs">Keep this receipt for returns/exchanges</p>
                <p class="text-gray-400 text-xs mt-2">Valid for 7 days from purchase date</p>
            </div>
        </div>

        <!-- Additional Print Controls -->
        <div class="no-print mt-4 text-center">
            <p class="text-gray-500 text-sm">Receipt will auto-format for thermal printer when printed</p>
        </div>
    </div>

    <script>
        // Auto-print option (uncomment if you want auto-print)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>