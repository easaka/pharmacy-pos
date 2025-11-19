<?php

namespace App\Http\Controllers;

use App\Models\StockEntry;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    public function store(Request $r){
        $data = $r->validate([
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1',
            'type'=>'required|in:in,out,adjustment',
            'batch_number'=>'nullable|string',
            'expiry_date'=>'nullable|date',
            'note'=>'nullable|string',
        ]);
        $data['created_by'] = auth()->id() ?? null;
        $entry = StockEntry::create($data);
        return response()->json($entry,201);
    }
}
