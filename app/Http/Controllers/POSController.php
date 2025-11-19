<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class POSController extends Controller
{
    public function checkout(Request $r)
    {
        $r->validate([
            'items'=>'required|array|min:1',
            'items.*.product_id'=>'required|exists:products,id',
            'items.*.quantity'=>'required|integer|min:1',
            'payment_method'=>'nullable|string',
            'paid'=>'numeric|min:0'
        ]);

        DB::transaction(function() use ($r, &$sale) {
            $invoice = 'INV-'.time();
            $sale = Sale::create([
                'invoice_no'=>$invoice,
                'user_id'=>auth()->id(),
                'total'=>0,
                'paid'=>$r->paid ?? 0,
                'payment_method'=>$r->payment_method ?? 'cash',
            ]);

            $total = 0;
            foreach($r->items as $it){
                $product = Product::findOrFail($it['product_id']);
                $unit = $product->selling_price;
                $subtotal = $unit * $it['quantity'];

                $saleItem = SaleItem::create([
                    'sale_id'=>$sale->id,
                    'product_id'=>$product->id,
                    'quantity'=>$it['quantity'],
                    'unit_price'=>$unit,
                    'subtotal'=>$subtotal
                ]);

                // create stock entry (out)
                StockEntry::create([
                    'product_id'=>$product->id,
                    'quantity'=>$it['quantity'],
                    'type'=>'out',
                    'note'=>'Sold in invoice '.$invoice,
                    'created_by'=>auth()->id()
                ]);

                $total += $subtotal;
            }

            $sale->update(['total'=>$total]);
        });

        return response()->json($sale->load('items.product'));
    }
}
