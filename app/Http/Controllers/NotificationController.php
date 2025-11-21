<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockEntry;

class NotificationController extends Controller
{
    public function index()
    {
        // Low Stock Alerts
        $lowStock = Product::all()->map(function ($p) {
            $p->current_stock = $p->currentStock();
            return $p;
        })->filter(function ($p) {
            return $p->current_stock <= ($p->reorder_level ?? 0);
        })->values();

        // Expiring Products (next 30 days) - expiry_date is in stock_entries table
        $expiringSoon = StockEntry::whereNotNull('expiry_date')
                                    ->whereDate('expiry_date', '<=', now()->addDays(30))
                                    ->whereDate('expiry_date', '>=', now())
                                    ->with('product')
                                    ->get();

        return response()->json([
            'low_stock_count'   => $lowStock->count(),
            'expiring_count'    => $expiringSoon->count(),
            'low_stock_items'   => $lowStock->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'current_stock' => $p->current_stock,
                    'reorder_level' => $p->reorder_level ?? 0,
                ];
            }),
            'expiring_items'    => $expiringSoon->map(function($e) {
                return [
                    'id' => $e->id,
                    'product' => $e->product ? [
                        'name' => $e->product->name,
                    ] : null,
                    'expiry_date' => $e->expiry_date,
                    'batch_number' => $e->batch_number,
                    'quantity' => $e->quantity,
                ];
            }),
        ]);
    }

    public function markAsRead($id)
    {
        // Placeholder for marking notifications as read
        return response()->json(['message' => 'Notification marked as read']);
    }
}
