<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockEntry;
use App\Models\Sale;

class ReportWebController extends Controller
{
    public function lowStock()
    {
        $products = Product::get()->map(function($p){
            $p->current_stock = $p->currentStock();
            return $p;
        })->filter(fn($p)=> $p->current_stock <= $p->reorder_level)->values();

        return view('reports.lowstock', compact('products'));
    }

    public function expirySoon(Request $r)
    {
        $days = (int)($r->days ?? 30);
        $date = now()->addDays($days)->toDateString();

        $entries = StockEntry::with('product')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date','<=',$date)
            ->orderBy('expiry_date')
            ->get();

        return view('reports.expiry', compact('entries','days'));
    }

    public function dailySales(Request $r)
    {
        $date = $r->date ?? now()->toDateString();
        $sales = Sale::with('items.product','user')
            ->whereDate('created_at',$date)
            ->get();

        $total = $sales->sum('total');

        return view('reports.daily', compact('sales','total','date'));
    }
}
