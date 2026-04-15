@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card glass-card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <p class="eyebrow mb-2">Receipt</p>
                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4">
                        <div>
                            <h1 class="section-title mb-1">{{ $order->order_number }}</h1>
                            <p class="text-muted mb-0">Placed on {{ $order->ordered_at?->format('F d, Y h:i A') }}</p>
                        </div>
                        <span class="badge text-bg-warning rounded-pill px-3 py-2">{{ strtoupper($order->status) }}</span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4"><strong>Customer</strong><div class="text-muted">{{ $order->customer_name }}</div></div>
                        <div class="col-md-4"><strong>Email</strong><div class="text-muted">{{ $order->customer_email }}</div></div>
                        <div class="col-md-4"><strong>Phone</strong><div class="text-muted">{{ $order->customer_phone }}</div></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
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
                                        <td class="small text-muted">{{ $item->size }}, {{ $item->sugar_level }}, {{ $item->ice_level }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>PHP {{ number_format($item->line_total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Grand total</th>
                                    <th>PHP {{ number_format($order->total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if ($order->notes)
                        <div class="mt-3">
                            <strong>Notes</strong>
                            <p class="text-muted mb-0">{{ $order->notes }}</p>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('orders.pdf', $order) }}" class="btn btn-cta">Download PDF receipt</a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-dark">Back to menu</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card glass-card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <p class="eyebrow mb-2">Bonus requirement</p>
                    <h2 class="section-title mb-3">Order QR code</h2>
                    <div class="qr-wrap mb-3">
                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate(route('orders.show', $order)) !!}
                    </div>
                    <p class="small text-muted mb-0">Scan to reopen this order receipt quickly.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
