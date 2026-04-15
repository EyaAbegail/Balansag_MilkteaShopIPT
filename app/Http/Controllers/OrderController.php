<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceiptMail;
use App\Models\Drink;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'drink_id' => ['required', 'exists:drinks,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'size' => ['required', 'in:Regular,Large,Jumbo'],
            'sugar_level' => ['required', 'in:0%,25%,50%,75%,100%'],
            'ice_level' => ['required', 'in:No Ice,Less Ice,Regular Ice'],
            'pickup_at' => ['nullable', 'date', 'after_or_equal:now'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $drink = Drink::query()
            ->where('is_available', true)
            ->findOrFail($validated['drink_id']);

        if ($drink->stock < $validated['quantity']) {
            throw ValidationException::withMessages([
                'drink_id' => 'Not enough stock available for this drink.',
            ]);
        }

        $sizeAdjustments = [
            'Regular' => 0,
            'Large' => 20,
            'Jumbo' => 35,
        ];

        $unitPrice = (float) $drink->price + $sizeAdjustments[$validated['size']];
        $lineTotal = $unitPrice * (int) $validated['quantity'];

        $order = DB::transaction(function () use ($validated, $drink, $unitPrice, $lineTotal) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'pickup_at' => $validated['pickup_at'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $lineTotal,
                'total' => $lineTotal,
            ]);

            $order->items()->create([
                'drink_id' => $drink->id,
                'drink_name' => $drink->name,
                'size' => $validated['size'],
                'sugar_level' => $validated['sugar_level'],
                'ice_level' => $validated['ice_level'],
                'quantity' => $validated['quantity'],
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ]);

            $drink->decrement('stock', (int) $validated['quantity']);

            return $order->load('items.drink', 'user');
        });

        Mail::to($order->customer_email)->send(new OrderReceiptMail($order));

        return redirect()
            ->route('orders.show', $order)
            ->with('status', 'Order placed successfully. A receipt email has been sent.');
    }

    public function show(Order $order): View
    {
        abort_unless(
            auth()->id() === $order->user_id || auth()->user()?->hasAnyRole('admin', 'staff'),
            403
        );

        $order->load('items.drink', 'user');

        return view('orders.show', compact('order'));
    }

    public function pdf(Order $order)
    {
        abort_unless(
            auth()->id() === $order->user_id || auth()->user()?->hasAnyRole('admin', 'staff'),
            403
        );

        $order->load('items.drink', 'user');

        return Pdf::loadView('pdf.receipt', compact('order'))
            ->download("receipt-{$order->order_number}.pdf");
    }
}
