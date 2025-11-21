<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockEntry;
use Illuminate\Support\Facades\DB;

class POSWebController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('pos.index', compact('products'));
    }

    // This endpoint will be used by form submit (server-side) to process sale
    public function checkout(Request $r)
    {
        $r->validate([
            'items'=>'required|array|min:1',
            'items.*.product_id'=>'required|exists:products,id',
            'items.*.quantity'=>'required|integer|min:1',
            'payment_method'=>'required|string',
            'paid'=>'required|numeric|min:0',
        ]);

        DB::transaction(function() use ($r, &$sale) {
            $invoice = 'INV-'.time();
            $sale = Sale::create([
                'invoice_no'=>$invoice,
                'user_id'=>auth()->id(),
                'total'=>0,
                'paid'=>$r->paid,
                'payment_method'=>$r->payment_method,
            ]);

            $total = 0;
            foreach($r->items as $it) {
                $product = Product::findOrFail($it['product_id']);
                $unit = $product->selling_price;
                $qty = (int)$it['quantity'];
                $subtotal = $unit * $qty;

                SaleItem::create([
                    'sale_id'=>$sale->id,
                    'product_id'=>$product->id,
                    'quantity'=>$qty,
                    'unit_price'=>$unit,
                    'subtotal'=>$subtotal,
                ]);

                // record stock out
                StockEntry::create([
                    'product_id'=>$product->id,
                    'quantity'=>$qty,
                    'type'=>'out',
                    'note'=>'Sold in '.$invoice,
                    'created_by'=>auth()->id()
                ]);

                $total += $subtotal;
            }

            $sale->update(['total'=>$total]);
        });

        // Automatically redirect to receipt after sale
    return redirect()->route('pos.receipt', $sale);
    }

    // Optionally view sales history
    public function salesHistory()
    {
        $sales = Sale::with('items.product','user')->latest()->paginate(20);
        return view('pos.sales', compact('sales'));
    }

public function receipt(Sale $sale)
{
    // Eager load all necessary relationships with null checks
    $sale->load([
        'items.product', 
        'user'
    ]);

    return view('pos.receipt', compact('sale'));
}

public function salesReport(Request $request)
{
    $start = $request->start;
    $end = $request->end;

    $query = Sale::query();

    if ($start && $end) {
        $query->whereBetween('created_at', [
            $start . ' 00:00:00',
            $end . ' 23:59:59'
        ]);
    } else {
        // Default: today's sales
        $query->whereDate('created_at', today());
    }

    $sales = $query->orderBy('created_at','desc')->get();
    $total = $sales->sum('total');

    return view('reports.sales', compact('sales', 'total'));
}

}
