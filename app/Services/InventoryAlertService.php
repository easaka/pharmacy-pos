<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockEntry;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryAlertService
{
    public function generateAlerts()
    {
        // Get settings correctly
        $settings = NotificationSetting::get();
        $now = Carbon::now();

        // Low stock alerts
        $lowStock = Product::whereColumn('quantity', '<=', DB::raw("reorder_level"))
            ->get();

        // Expiry alerts - expiry_date is in stock_entries table, not products
        $expiryDays = $settings['expiry_days_warning'] ?? 30;

        $expiringSoon = StockEntry::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', $now->copy()->addDays($expiryDays))
            ->whereDate('expiry_date', '>=', $now)
            ->with('product')
            ->get();

        return [
            'low_stock'     => $lowStock,
            'expiring_soon' => $expiringSoon,
            'settings'      => $settings,
        ];
    }
}
