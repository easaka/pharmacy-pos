<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockEntry;

class StockWebController extends Controller
{
    public function index()
    {
        $entries = StockEntry::with('product')->latest()->paginate(20);
        return view('stock.index', compact('entries'));
    }

    public function create()
    {
        $products = Product::all();
        return view('stock.create', compact('products'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1',
            'type'=>'required|in:in,out,adjustment',
            'batch_number'=>'nullable|string',
            'expiry_date'=>'nullable|date',
            'note'=>'nullable|string',
        ]);

        $data['created_by'] = auth()->id();
        StockEntry::create($data);

        return redirect()->route('stock.index')->with('success','Stock recorded.');
    }
}
