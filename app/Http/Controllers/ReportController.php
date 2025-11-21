<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\StockEntry;
use App\Models\Purchase;      // optional: create if you don't have it
use App\Models\PurchaseItem;  // optional: create if you don't have it
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReportController extends Controller
{
    // Helper: parse date range (returns [$start, $end])
    protected function parseRange(Request $r)
    {
        $start = $r->query('start') ?: now()->startOfMonth()->toDateString();
        $end = $r->query('end') ?: now()->toDateString();
        return [$start, $end];
    }

    // Dashboard
    public function dashboard()
    {
        // quick KPIs
        $todaySales = Sale::whereDate('created_at', today())->sum('total');
        $stockValue = Product::all()->sum(function($p){
            return ($p->cost_price ?? 0) * ($p->currentStock() ?? 0);
        });
        $lowCount = Product::all()->filter(function($p){ return $p->currentStock() <= $p->reorder_level; })->count();
        $expiringSoon = StockEntry::whereNotNull('expiry_date')->whereDate('expiry_date','<=', now()->addDays(30))->count();

        return view('reports.dashboard', compact('todaySales','stockValue','lowCount','expiringSoon'));
    }

    /* ----------------------------
       SALES
       ---------------------------- */
    public function sales(Request $request)
    {
        [$start,$end] = $this->parseRange($request);

        $sales = Sale::with('user')
            ->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"])
            ->orderBy('created_at','desc')
            ->get();

        $total = $sales->sum('total');
        $paid = $sales->sum('paid');
        $outstanding = $total - $paid;

        // chart: daily totals
        $period = new \DatePeriod(new \DateTime($start), new \DateInterval('P1D'), (new \DateTime($end))->modify('+1 day'));
        $labels = []; $chartSales = []; $chartPaid = [];
        foreach($period as $d){
            $day = $d->format('Y-m-d');
            $labels[] = $day;
            $chartSales[] = (float) Sale::whereDate('created_at',$day)->sum('total');
            $chartPaid[] = (float) Sale::whereDate('created_at',$day)->sum('paid');
        }

        return view('reports.sales', [
            'sales'=>$sales,
            'total'=>$total,
            'paid'=>$paid,
            'outstanding'=>$outstanding,
            'chartLabels'=>$labels,
            'chartSales'=>$chartSales,
            'chartPaid'=>$chartPaid,
            'start'=>$start,
            'end'=>$end,
        ]);
    }

    public function exportSalesExcel(Request $request)
    {
        [$start,$end] = $this->parseRange($request);
        $sales = Sale::with('user')->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"])->get();

        $filename = "sales_{$start}_to_{$end}.xlsx";

        $writer = SimpleExcelWriter::streamDownload($filename);
        foreach($sales as $s){
            $writer->addRow([
                'invoice' => $s->invoice_no,
                'date' => $s->created_at->toDateTimeString(),
                'cashier' => $s->user->name ?? '',
                'total' => $s->total,
                'paid' => $s->paid,
                'payment_method' => $s->payment_method ?? '',
            ]);
        }
        return $writer->toBrowser();
    }

    public function exportSalesPdf(Request $request)
{
    [$start,$end] = $this->parseRange($request);
    $sales = Sale::with('user')->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"])->get();
    
    // Calculate totals
    $total = $sales->sum('total');
    $paid = $sales->sum('paid');

    // Pass all required variables to the view
    $pdf = PDF::loadView('reports.pdf.sales', compact('sales', 'total', 'paid', 'start', 'end'));
    return $pdf->download("sales_{$start}_to_{$end}.pdf");
}

    /* ----------------------------
       PURCHASES (optional models)
       ---------------------------- */
    public function purchases(Request $request)
    {
        [$start,$end] = $this->parseRange($request);

        // Check if purchases table exists
        try {
            if (!class_exists(Purchase::class)) {
                throw new \Exception('Purchase model not found');
            }
            
            // Test if table exists by making a simple query
            Purchase::count();
            
        } catch (\Exception $e) {
            // If Purchase model or table doesn't exist, return empty view
            return view('reports.purchases', [
                'purchases' => collect(),
                'total' => 0,
                'labels' => [],
                'data' => [],
                'start' => $start,
                'end' => $end
            ]);
        }

        // If we get here, the table exists and we can query it
        $purchases = Purchase::with('items.product')
            ->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"])
            ->get();

        $total = $purchases->sum('total');

        $period = new \DatePeriod(new \DateTime($start), new \DateInterval('P1D'), (new \DateTime($end))->modify('+1 day'));
        $labels = []; 
        $data = [];
        
        foreach($period as $d){
            $day = $d->format('Y-m-d');
            $labels[] = $day;
            $data[] = (float) Purchase::whereDate('created_at', $day)->sum('total');
        }

        return view('reports.purchases', compact('purchases', 'total', 'labels', 'data', 'start', 'end'));
    }

    public function exportPurchasesExcel(Request $request)
    {
        if (!class_exists(Purchase::class)) abort(404,'Purchase model not found');

        [$start,$end] = $this->parseRange($request);
        $purchases = Purchase::with('items')->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"])->get();

        $filename = "purchases_{$start}_to_{$end}.xlsx";
        $writer = SimpleExcelWriter::streamDownload($filename);
        foreach($purchases as $p){
            $writer->addRow([
                'purchase_no'=>$p->id,
                'date'=>$p->created_at->toDateTimeString(),
                'supplier'=>$p->supplier_name ?? '',
                'total'=>$p->total,
            ]);
        }
        return $writer->toBrowser();
    }

    public function exportPurchasesPdf(Request $request)
    {
        if (!class_exists(Purchase::class)) abort(404,'Purchase model not found');

        [$start,$end] = $this->parseRange($request);
        $purchases = Purchase::with('items')->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"])->get();
        $total = $purchases->sum('total');

        $pdf = PDF::loadView('reports.pdf.purchases', compact('purchases','total','start','end'));
        return $pdf->download("purchases_{$start}_to_{$end}.pdf");
    }

    /* ----------------------------
       FAST MOVING
       ---------------------------- */
    public function fastMoving(Request $request)
    {
        [$start,$end] = $this->parseRange($request);

        $items = DB::table('sale_items')
            ->join('sales','sale_items.sale_id','=','sales.id')
            ->join('products','sale_items.product_id','=','products.id')
            ->select('products.id','products.name','products.sku', DB::raw('SUM(sale_items.quantity) as qty_sold'), DB::raw('SUM(sale_items.subtotal) as revenue'))
            ->whereBetween('sales.created_at', ["{$start} 00:00:00", "{$end} 23:59:59"])
            ->groupBy('products.id','products.name','products.sku')
            ->orderByDesc('qty_sold')
            ->get();

        $labels = $items->pluck('name')->take(10);
        $data = $items->pluck('qty_sold')->take(10);

        return view('reports.fastmoving', compact('items','labels','data','start','end'));
    }

    public function exportFastMovingExcel(Request $request)
    {
        [$start,$end] = $this->parseRange($request);
        $items = DB::table('sale_items')
            ->join('sales','sale_items.sale_id','=','sales.id')
            ->join('products','sale_items.product_id','=','products.id')
            ->select('products.sku','products.name', DB::raw('SUM(sale_items.quantity) as qty_sold'), DB::raw('SUM(sale_items.subtotal) as revenue'))
            ->whereBetween('sales.created_at', ["{$start} 00:00:00", "{$end} 23:59:59"])
            ->groupBy('products.sku','products.name')
            ->orderByDesc('qty_sold')
            ->get();

        $filename = "fastmoving_{$start}_to_{$end}.xlsx";
        $writer = SimpleExcelWriter::streamDownload($filename);
        foreach($items as $it) {
            $writer->addRow((array)$it);
        }
        return $writer->toBrowser();
    }

    public function exportFastMovingPdf(Request $request)
    {
        [$start,$end] = $this->parseRange($request);
        $items = DB::table('sale_items')
            ->join('sales','sale_items.sale_id','=','sales.id')
            ->join('products','sale_items.product_id','=','products.id')
            ->select('products.sku','products.name', DB::raw('SUM(sale_items.quantity) as qty_sold'), DB::raw('SUM(sale_items.subtotal) as revenue'))
            ->whereBetween('sales.created_at', ["{$start} 00:00:00", "{$end} 23:59:59"])
            ->groupBy('products.sku','products.name')
            ->orderByDesc('qty_sold')
            ->get();

        $pdf = PDF::loadView('reports.pdf.fastmoving', compact('items','start','end'));
        return $pdf->download("fastmoving_{$start}_to_{$end}.pdf");
    }

    /* ----------------------------
       PROFIT & LOSS
       ---------------------------- */
    public function profitLoss(Request $request)
    {
        [$start,$end] = $this->parseRange($request);

        $items = SaleItem::with('product','sale')
            ->whereHas('sale', function($q) use($start,$end){
                $q->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
            })->get();

        $totalRevenue = $items->sum('subtotal');

        $totalCost = $items->sum(function($it){
            return ($it->product->cost_price ?? 0) * $it->quantity;
        });

        $profit = $totalRevenue - $totalCost;

        // Chart: daily revenue vs cost
        $period = new \DatePeriod(new \DateTime($start), new \DateInterval('P1D'), (new \DateTime($end))->modify('+1 day'));
        $labels=[]; $rev=[]; $cost=[];
        foreach($period as $d){
            $labels[] = $d->format('Y-m-d');
            $rev[] = (float) Sale::whereDate('created_at',$d->format('Y-m-d'))->sum('total');
            // cost: sum of (cost_price * qty) for that day
            $c = SaleItem::with('product')->whereHas('sale', function($q) use($d){
                $q->whereDate('created_at',$d->format('Y-m-d'));
            })->get()->sum(function($it){
                return ($it->product->cost_price ?? 0) * $it->quantity;
            });
            $cost[] = (float) $c;
        }

        return view('reports.profitloss', compact('items','totalRevenue','totalCost','profit','labels','rev','cost','start','end'));
    }

    public function exportProfitLossExcel(Request $request)
    {
        [$start,$end] = $this->parseRange($request);

        $items = SaleItem::with('product','sale')
            ->whereHas('sale', function($q) use($start,$end){
                $q->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
            })->get();

        $filename = "profitloss_{$start}_to_{$end}.xlsx";
        $writer = SimpleExcelWriter::streamDownload($filename);
        foreach($items as $it){
            $writer->addRow([
                'invoice'=>$it->sale->invoice_no ?? '',
                'product'=>$it->product->name ?? '',
                'qty'=>$it->quantity,
                'unit_price'=>$it->unit_price,
                'revenue'=>$it->subtotal,
                'cost'=>($it->product->cost_price ?? 0) * $it->quantity,
                'profit'=>$it->subtotal - (($it->product->cost_price ?? 0) * $it->quantity),
            ]);
        }
        return $writer->toBrowser();
    }

    public function exportProfitLossPdf(Request $request)
    {
        [$start,$end] = $this->parseRange($request);
        $items = SaleItem::with('product','sale')
            ->whereHas('sale', function($q) use($start,$end){
                $q->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
            })->get();

        $totalRevenue = $items->sum('subtotal');
        $totalCost = $items->sum(function($it){
            return ($it->product->cost_price ?? 0) * $it->quantity;
        });
        $profit = $totalRevenue - $totalCost;

        $pdf = PDF::loadView('reports.pdf.profitloss', compact('items','totalRevenue','totalCost','profit','start','end'));
        return $pdf->download("profitloss_{$start}_to_{$end}.pdf");
    }

    /* ----------------------------
       STOCK VALUATION
       ---------------------------- */
    public function stockValuation(Request $request)
    {
        $products = Product::with('stockEntries')->get()->map(function($p){
            $p->current_stock = $p->currentStock();
            $p->stock_value = ($p->cost_price ?? 0) * $p->current_stock;
            return $p;
        });

        $totalValue = $products->sum('stock_value');
        $top = $products->sortByDesc('stock_value')->take(10);
        $labels = $top->pluck('name');
        $data = $top->pluck('stock_value');

        return view('reports.stockvaluation', compact('products','totalValue','labels','data'));
    }

    public function exportStockValuationExcel(Request $request)
    {
        $products = Product::with('stockEntries')->get()->map(function($p){
            $p->current_stock = $p->currentStock();
            $p->stock_value = ($p->cost_price ?? 0) * $p->current_stock;
            return $p;
        });

        $filename = "stock_valuation_".now()->toDateString().".xlsx";
        $writer = SimpleExcelWriter::streamDownload($filename);
        foreach($products as $p){
            $writer->addRow([
                'sku'=>$p->sku,
                'name'=>$p->name,
                'stock'=>$p->current_stock,
                'cost_price'=>$p->cost_price,
                'stock_value'=>$p->stock_value,
            ]);
        }
        return $writer->toBrowser();
    }

    public function exportStockValuationPdf(Request $request)
    {
        $products = Product::with('stockEntries')->get()->map(function($p){
            $p->current_stock = $p->currentStock();
            $p->stock_value = ($p->cost_price ?? 0) * $p->current_stock;
            return $p;
        });
        $totalValue = $products->sum('stock_value');
        $pdf = PDF::loadView('reports.pdf.stockvaluation', compact('products','totalValue'));
        return $pdf->download("stock_valuation_".now()->toDateString().".pdf");
    }

    /* ----------------------------
       EXPIRY
       ---------------------------- */
    public function expiry(Request $request)
    {
        $days = (int)($request->query('days') ?? 30);
        $date = now()->addDays($days)->toDateString();

        $entries = StockEntry::with('product')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date','<=',$date)
            ->orderBy('expiry_date')
            ->get();

        return view('reports.expiry', compact('entries','days'));
    }

    public function exportExpiryExcel(Request $request)
    {
        $days = (int)($request->query('days') ?? 30);
        $date = now()->addDays($days)->toDateString();

        $entries = StockEntry::with('product')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date','<=',$date)
            ->orderBy('expiry_date')
            ->get();

        $filename = "expiry_soon_".now()->toDateString().".xlsx";
        $writer = SimpleExcelWriter::streamDownload($filename);
        foreach($entries as $e){
            $writer->addRow([
                'product'=>$e->product->name ?? '',
                'batch'=>$e->batch_number,
                'expiry'=>$e->expiry_date,
                'qty'=>$e->quantity
            ]);
        }
        return $writer->toBrowser();
    }

    public function exportExpiryPdf(Request $request)
    {
        $days = (int)($request->query('days') ?? 30);
        $date = now()->addDays($days)->toDateString();

        $entries = StockEntry::with('product')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date','<=',$date)
            ->orderBy('expiry_date')
            ->get();

        $pdf = PDF::loadView('reports.pdf.expiry', compact('entries','days'));
        return $pdf->download("expiry_soon_".now()->toDateString().".pdf");
    }

    /* ----------------------------
       LOW STOCK
       ---------------------------- */
    public function lowStock(Request $request)
    {
        $products = Product::all()->map(function($p){
            $p->current_stock = $p->currentStock();
            return $p;
        })->filter(fn($p)=> $p->current_stock <= ($p->reorder_level ?? 0))->values();

        return view('reports.lowstock', compact('products'));
    }

    public function exportLowStockExcel(Request $request)
    {
        $products = Product::all()->map(function($p){
            $p->current_stock = $p->currentStock();
            return $p;
        })->filter(fn($p)=> $p->current_stock <= ($p->reorder_level ?? 0))->values();

        $filename = "low_stock_".now()->toDateString().".xlsx";
        $writer = SimpleExcelWriter::streamDownload($filename);
        foreach($products as $p){
            $writer->addRow([
                'sku'=>$p->sku,
                'name'=>$p->name,
                'stock'=>$p->current_stock,
                'reorder_level'=>$p->reorder_level
            ]);
        }
        return $writer->toBrowser();
    }

    public function exportLowStockPdf(Request $request)
    {
        $products = Product::all()->map(function($p){
            $p->current_stock = $p->currentStock();
            return $p;
        })->filter(fn($p)=> $p->current_stock <= ($p->reorder_level ?? 0))->values();

        $pdf = PDF::loadView('reports.pdf.lowstock', compact('products'));
        return $pdf->download("low_stock_".now()->toDateString().".pdf");
    }
}
