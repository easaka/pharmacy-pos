<?php
namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $start;
    protected $end;
    public function __construct($start,$end){ $this->start=$start; $this->end=$end; }

    public function collection()
    {
        return Sale::with('user')
            ->whereBetween('created_at', ["{$this->start} 00:00:00", "{$this->end} 23:59:59"])
            ->get()
            ->map(function($s){
                return [
                    'invoice_no'=>$s->invoice_no,
                    'date'=>$s->created_at->toDateTimeString(),
                    'user'=>$s->user->name ?? '',
                    'total'=>$s->total,
                    'paid'=>$s->paid,
                    'payment_method'=>$s->payment_method ?? ''
                ];
            });
    }

    public function headings(): array
    {
        return ['Invoice','Date','Cashier','Total','Paid','Payment Method'];
    }
}
