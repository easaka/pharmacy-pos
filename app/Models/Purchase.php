<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'supplier_contact',
        'total',
        'notes',
        'purchase_date'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total' => 'decimal:2'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}