<?php
namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FastMovingExport implements FromCollection, WithHeadings
{
    protected $start; protected $end;
    public function __construct($start,$end){ $this->start=$start; $this->end=$end; }

    public function collection()
    {
        $items = DB::table('sale_items')
            ->join('sales','sale_items.sale_id','=','sales.id')
            ->join('products','sale_items.product_id','=','products.id')
            ->select('products.sku','products.name', DB::raw('SUM(sale_items.quantity) as qty_sold'), DB::raw('SUM(sale_items.subtotal) as revenue'))
            ->whereBetween('sales.created_at', ["{$this->start} 00:00:00", "{$this->end} 23:59:59"])
            ->groupBy('products.sku','products.name')
            ->orderByDesc('qty_sold')
            ->get();

        return $items->map(fn($i)=> (array)$i);
    }

    public function headings(): array
    {
        return ['SKU','Name','Quantity Sold','Revenue'];
    }
}
