<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Milktea Shop Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #2d2018; font-size: 12px; }
        h1, h2 { margin: 0 0 10px; }
        .meta { margin-bottom: 20px; }
        .cards { margin-bottom: 18px; }
        .card { display: inline-block; width: 31%; padding: 10px; margin-right: 1%; background: #f6eee5; border-radius: 8px; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #d8c2ae; padding: 8px; text-align: left; }
        th { background: #efe1d2; }
    </style>
</head>
<body>
    <h1>Milktea Shop Sales Report</h1>
    <div class="meta">Covered period: {{ $from }} to {{ $to }}</div>

    <div class="cards">
        <div class="card"><strong>Total orders</strong><div>{{ $summary->orders_count ?? 0 }}</div></div>
        <div class="card"><strong>Completed revenue</strong><div>PHP {{ number_format($summary->completed_revenue ?? 0, 2) }}</div></div>
        <div class="card"><strong>Active orders</strong><div>{{ $summary->active_orders ?? 0 }}</div></div>
    </div>

    <h2>Best-Selling Drinks</h2>
    <table>
        <thead>
            <tr>
                <th>Drink</th>
                <th>Category</th>
                <th>Cups sold</th>
                <th>Jumbo cups</th>
                <th>Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bestSellers as $item)
                <tr>
                    <td>{{ $item->drink_name }}</td>
                    <td>{{ $item->category_name }}</td>
                    <td>{{ $item->cups_sold }}</td>
                    <td>{{ $item->jumbo_cups }}</td>
                    <td>PHP {{ number_format($item->total_sales, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
