<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    public function creating(Order $order): void
    {
        if (blank($order->order_number)) {
            $order->order_number = 'MT-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        if (blank($order->qr_token)) {
            $order->qr_token = (string) Str::uuid();
        }

        if (blank($order->ordered_at)) {
            $order->ordered_at = now();
        }
    }
}
