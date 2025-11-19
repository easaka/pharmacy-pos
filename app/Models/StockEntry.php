<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockEntry extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','quantity','batch_number','expiry_date','type','created_by','note'];

    public function product(){ return $this->belongsTo(Product::class); }
}

