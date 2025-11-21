<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'supplier');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('supplier', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['name', 'sku', 'selling_price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories','suppliers'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'sku'=>'nullable|string|unique:products,sku',
            'name'=>'required|string',
            'category_id'=>'nullable|exists:categories,id',
            'supplier_id'=>'nullable|exists:suppliers,id',
            'cost_price'=>'nullable|numeric',
            'selling_price'=>'nullable|numeric',
            'reorder_level'=>'nullable|integer',
            'description'=>'nullable|string',
        ]);

        Product::create($data);
        return redirect()->route('products.index')->with('success','Product created.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product','categories','suppliers'));
    }

    public function update(Request $r, Product $product)
    {
        $data = $r->validate([
            'sku'=>'nullable|string|unique:products,sku,'.$product->id,
            'name'=>'sometimes|required|string',
            'category_id'=>'nullable|exists:categories,id',
            'supplier_id'=>'nullable|exists:suppliers,id',
            'cost_price'=>'nullable|numeric',
            'selling_price'=>'nullable|numeric',
            'reorder_level'=>'nullable|integer',
            'description'=>'nullable|string',
        ]);

        $product->update($data);
        return redirect()->route('products.index')->with('success','Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success','Product deleted.');
    }

    public function show(Product $product)
    {
        $product->load('stockEntries');
        $product->current_stock = $product->currentStock();
        return view('products.show', compact('product'));
    }
}
