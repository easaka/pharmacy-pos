<!DOCTYPE html>
<html>
<head>
    <title>Purchases Report - {{ $start }} to {{ $end }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Purchases Report</h1>
        <p><strong>Period:</strong> {{ $start }} to {{ $end }}</p>
    </div>

    <div class="summary">
        <h2>Total Purchases: GHS {{ number_format($total, 2) }}</h2>
        <p>Number of Purchase Orders: {{ $purchases->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Purchase #</th>
                <th>Supplier</th>
                <th>Contact</th>
                <th class="text-right">Total Amount (GHS)</th>
                <th>Purchase Date</th>
                <th>Items Count</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $purchase)
            <tr>
                <td>#{{ $purchase->id }}</td>
                <td>{{ $purchase->supplier_name ?? 'No Supplier' }}</td>
                <td>{{ $purchase->supplier_contact ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($purchase->total, 2) }}</td>
                <td>{{ $purchase->purchase_date ? $purchase->purchase_date->format('Y-m-d') : $purchase->created_at->format('Y-m-d') }}</td>
                <td>{{ $purchase->items->count() ?? 0 }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No purchases found for the selected period</td>
            </tr>
            @endforelse
        </tbody>
        @if($purchases->count() > 0)
        <tfoot class="total-row">
            <tr>
                <td colspan="3">TOTAL</td>
                <td class="text-right">GHS {{ number_format($total, 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- Summary Statistics -->
    @if($purchases->count() > 0)
    <div style="margin-top: 30px;">
        <h3>Summary Statistics</h3>
        <table style="width: auto;">
            <tr>
                <td><strong>Total Purchase Orders:</strong></td>
                <td>{{ $purchases->count() }}</td>
            </tr>
            <tr>
                <td><strong>Average Purchase Value:</strong></td>
                <td>GHS {{ number_format($purchases->avg('total'), 2) }}</td>
            </tr>
            <tr>
                <td><strong>Largest Purchase:</strong></td>
                <td>GHS {{ number_format($purchases->max('total'), 2) }}</td>
            </tr>
            <tr>
                <td><strong>Smallest Purchase:</strong></td>
                <td>GHS {{ number_format($purchases->min('total'), 2) }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div style="margin-top: 40px; font-size: 12px; color: #666; text-align: center;">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>Pharmacy POS System</p>
    </div>
</body>
</html>