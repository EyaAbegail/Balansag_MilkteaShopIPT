<p>Hello {{ $order->customer_name }},</p>

<p>Thanks for ordering from Milktea Shop. Your order <strong>{{ $order->order_number }}</strong> has been received.</p>

<p>Status: {{ ucfirst($order->status) }}</p>
<p>Total: PHP {{ number_format($order->total, 2) }}</p>

<p>Your PDF receipt is attached to this email.</p>

<p>Enjoy your drink!</p>
