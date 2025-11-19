<!DOCTYPE html>
<html>
<head>
    <title>Fast Moving Items Report - {{ $start }} to {{ $end }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fast Moving Items Report</h1>
        <p><strong>Period:</strong> {{ $start }} to {{ $end }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product Name</th>
                <th>Quantity Sold</th>
                <th>Revenue (GHS)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $item->sku ?? 'N/A' }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->qty_sold }}</td>
                <td>{{ number_format($item->revenue, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">No sales data found for the selected period</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 12px; color: #666; text-align: center;">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>