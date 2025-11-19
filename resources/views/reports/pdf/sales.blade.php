<!DOCTYPE html>
<html>
<head>
    <title>Sales Report - {{ $start }} to {{ $end }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p><strong>Period:</strong> {{ $start }} to {{ $end }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Sales:</strong> GHS {{ number_format($total, 2) }}</p>
        <p><strong>Total Paid:</strong> GHS {{ number_format($paid, 2) }}</p>
        <p><strong>Outstanding:</strong> GHS {{ number_format($total - $paid, 2) }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Cashier</th>
                <th>Total (GHS)</th>
                <th>Paid (GHS)</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_no }}</td>
                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $sale->user->name ?? 'N/A' }}</td>
                <td>{{ number_format($sale->total, 2) }}</td>
                <td>{{ number_format($sale->paid, 2) }}</td>
                <td>{{ ucfirst($sale->payment_method) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No sales found for the selected period</td>
            </tr>
            @endforelse
        </tbody>
        @if($sales->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="3"><strong>TOTAL</strong></td>
                <td><strong>GHS {{ number_format($total, 2) }}</strong></td>
                <td><strong>GHS {{ number_format($paid, 2) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>
    
    <div style="margin-top: 30px; font-size: 12px; color: #666; text-align: center;">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>