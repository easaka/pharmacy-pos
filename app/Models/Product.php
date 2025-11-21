<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

    public function purchases()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function sales()
    {
        return $this->hasMany(SaleItem::class);
    }

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
        return max(0, $in - $out); // Ensure non-negative
    }

    /**
     * Accessor for current_stock attribute
     */
    public function getCurrentStockAttribute()
    {
        return $this->currentStock();
    }

}
