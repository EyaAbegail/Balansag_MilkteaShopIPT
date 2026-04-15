@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <p class="eyebrow mb-1">Operations</p>
        <h1 class="section-title mb-0">Order queue</h1>
    </div>

    <div class="row g-4">
        @foreach ($orders as $order)
            <div class="col-lg-6">
                <div class="card glass-card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h2 class="h5 mb-1">{{ $order->order_number }}</h2>
                                <p class="small text-muted mb-0">{{ $order->customer_name }} • {{ $order->customer_email }}</p>
                            </div>
                            <span class="badge text-bg-light">{{ ucfirst($order->status) }}</span>
                        </div>

                        <ul class="list-unstyled small text-muted mb-3">
                            @foreach ($order->items as $item)
                                <li>{{ $item->quantity }}x {{ $item->drink_name }} ({{ $item->size }})</li>
                            @endforeach
                        </ul>

                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select">
                                @foreach (['pending', 'preparing', 'ready', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-cta">Update</button>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-dark">View</a>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
