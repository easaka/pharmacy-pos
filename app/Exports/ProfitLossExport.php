<?php
namespace App\Exports;

use App\Models\SaleItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfitLossExport implements FromCollection, WithHeadings
{
    protected $start; protected $end;
    public function __construct($start,$end){ $this->start=$start; $this->end=$end; }

    public function collection()
    {
        $items = SaleItem::with('product')
            ->whereHas('sale', fn($q)=>$q->whereBetween('created_at', ["{$this->start} 00:00:00", "{$this->end} 23:59:59"]))
            ->get()
            ->map(function($it){
                $cost = ($it->product->cost_price ?? 0) * $it->quantity;
                $revenue = $it->subtotal;
                $profit = $revenue - $cost;
                return [
                    'invoice'=>$it->sale->invoice_no ?? '',
                    'product'=>$it->product->name ?? '',
                    'quantity'=>$it->quantity,
                    'unit_price'=>$it->unit_price,
                    'revenue'=>$revenue,
                    'cost'=>$cost,
                    'profit'=>$profit
                ];
            });

        return $items;
    }

    public function headings(): array
    {
        return ['Invoice','Product','Quantity','Unit Price','Revenue','Cost','Profit'];
    }
}
