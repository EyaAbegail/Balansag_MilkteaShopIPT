<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $order->order_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #312218; font-size: 12px; }
        h1 { margin: 0 0 6px; }
        .muted { color: #6d5a4d; }
        .summary { margin: 18px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { border: 1px solid #d9c7b8; padding: 8px; text-align: left; }
        th { background: #f4e8db; }
    </style>
</head>
<body>
    <h1>Milktea Shop Receipt</h1>
    <div class="muted">Order #: {{ $order->order_number }}</div>
    <div class="muted">Date: {{ $order->ordered_at?->format('F d, Y h:i A') }}</div>

    <div class="summary">
        <strong>Customer:</strong> {{ $order->customer_name }}<br>
        <strong>Email:</strong> {{ $order->customer_email }}<br>
        <strong>Phone:</strong> {{ $order->customer_phone }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Drink</th>
                <th>Customizations</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->drink_name }}</td>
                    <td>{{ $item->size }}, {{ $item->sugar_level }}, {{ $item->ice_level }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>PHP {{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right;">Grand total</th>
                <th>PHP {{ number_format($order->total, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
