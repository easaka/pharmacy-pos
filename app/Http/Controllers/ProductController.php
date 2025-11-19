<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        return Product::with(['category','supplier'])->paginate(25);
    }

    public function store(Request $r){
        $data = $r->validate([
            'sku'=>'nullable|string|unique:products,sku',
            'name'=>'required|string',
            'category_id'=>'nullable|exists:categories,id',
            'supplier_id'=>'nullable|exists:suppliers,id',
            'cost_price'=>'numeric',
            'selling_price'=>'numeric',
            'reorder_level'=>'integer',
            'description'=>'nullable',
        ]);
        $product = Product::create($data);
        return response()->json($product,201);
    }

    public function show(Product $product){
        $product->load('stockEntries');
        $product->current_stock = $product->currentStock();
        return $product;
    }

    public function update(Request $r, Product $product){
        $data = $r->validate([
            'name'=>'sometimes|required',
            'selling_price'=>'sometimes|numeric',
            // ...
        ]);
        $product->update($data);
        return response()->json($product);
    }

    public function destroy(Product $product){
        $product->delete();
        return response()->noContent();
    }
}

