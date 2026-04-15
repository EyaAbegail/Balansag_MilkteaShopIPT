@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="eyebrow mb-1">Reporting</p>
            <h1 class="section-title mb-0">Sales analytics</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.pdf', ['from' => $from, 'to' => $to]) }}" class="btn btn-cta">Export PDF</a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">Open queue</a>
        </div>
    </div>

    <div class="card glass-card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">From</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">To</label>
                    <input type="date" name="to" value="{{ $to }}" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-cta w-100">Apply filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="metric-card">
                <span>Total orders</span>
                <strong>{{ $summary->orders_count ?? 0 }}</strong>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card">
                <span>Completed revenue</span>
                <strong>PHP {{ number_format($summary->completed_revenue ?? 0, 2) }}</strong>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card">
                <span>Active orders</span>
                <strong>{{ $summary->active_orders ?? 0 }}</strong>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card glass-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="eyebrow mb-2">Complex query output</p>
                    <h2 class="section-title mb-3">Best-selling drinks by category</h2>
                    <div class="table-responsive">
                        <table class="table">
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
                                @forelse ($bestSellers as $item)
                                    <tr>
                                        <td>{{ $item->drink_name }}</td>
                                        <td>{{ $item->category_name }}</td>
                                        <td>{{ $item->cups_sold }}</td>
                                        <td>{{ $item->jumbo_cups }}</td>
                                        <td>PHP {{ number_format($item->total_sales, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">No sales data in this range.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card glass-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="eyebrow mb-2">Recent activity</p>
                    <h2 class="section-title mb-3">Latest orders</h2>
                    @forelse ($recentOrders as $order)
                        <div class="recent-order">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $order->order_number }}</strong>
                                <span>{{ ucfirst($order->status) }}</span>
                            </div>
                            <div class="small text-muted">{{ $order->customer_name }} • PHP {{ number_format($order->total, 2) }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No recent orders for this range.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
