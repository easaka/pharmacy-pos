<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku','name','category_id','supplier_id','cost_price','selling_price','reorder_level','description'
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function stockEntries() { return $this->hasMany(StockEntry::class); }

    public function currentStock()
    {
        // simple current stock: sum of in - sum of out
        $in = $this->stockEntries()->where('type','in')->sum('quantity');
        $out = $this->stockEntries()->where('type','out')->sum('quantity');
        return $in - $out;
    }
}
