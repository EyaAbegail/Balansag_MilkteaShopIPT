@extends('layouts.app')

@section('content')
<section class="hero-section">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <p class="eyebrow mb-3">Mini Capstone Project</p>
                <h1 class="display-4 fw-bold mb-3">Milktea Shop ordering and reporting system</h1>
                <p class="lead text-muted mb-4">Browse drinks, place a customized order, receive a receipt by email, and let admins manage the menu with sales reports and PDF exports.</p>
                <div class="d-flex flex-wrap gap-3">
                    <span class="badge badge-soft">Roles & permissions</span>
                    <span class="badge badge-soft">Image uploads</span>
                    <span class="badge badge-soft">Email + PDF</span>
                    <span class="badge badge-soft">Observer + QR</span>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-card shadow-lg">
                    <h2 class="h4 mb-3">Featured drinks</h2>
                    @forelse ($featuredDrinks as $drink)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <div>
                                <strong>{{ $drink->name }}</strong>
                                <div class="small text-muted">{{ $drink->category->name ?? 'Signature' }}</div>
                            </div>
                            <span class="fw-semibold">PHP {{ number_format($drink->price, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No featured drinks yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            @forelse ($categories as $category)
                <section class="menu-block mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <p class="eyebrow mb-1">{{ $category->name }}</p>
                            <h2 class="section-title mb-0">{{ $category->description }}</h2>
                        </div>
                    </div>

                    <div class="row g-4">
                        @foreach ($category->drinks as $drink)
                            <div class="col-md-6">
                                <div class="card menu-card h-100 border-0 shadow-sm">
                                    @if ($drink->image_path)
                                        <img src="{{ Storage::disk('public')->url($drink->image_path) }}" alt="{{ $drink->name }}" class="menu-image">
                                    @else
                                        <div class="menu-image placeholder-image">Freshly brewed</div>
                                    @endif

                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h3 class="h5 mb-1">{{ $drink->name }}</h3>
                                                <p class="text-muted small mb-0">{{ $drink->description }}</p>
                                            </div>
                                            <span class="price-tag">PHP {{ number_format($drink->price, 2) }}</span>
                                        </div>

                                        <div class="small text-muted mb-3">Stock: {{ $drink->stock }} cups</div>

                                        @auth
                                            <form action="{{ route('orders.store') }}" method="POST" class="row g-2">
                                                @csrf
                                                <input type="hidden" name="drink_id" value="{{ $drink->id }}">
                                                <input type="hidden" name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}">
                                                <input type="hidden" name="customer_email" value="{{ old('customer_email', auth()->user()->email) }}">
                                                <input type="hidden" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone) }}">

                                                <div class="col-6">
                                                    <label class="form-label small">Size</label>
                                                    <select name="size" class="form-select form-select-sm">
                                                        <option>Regular</option>
                                                        <option>Large</option>
                                                        <option>Jumbo</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">Quantity</label>
                                                    <input type="number" min="1" max="20" name="quantity" value="1" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">Sugar</label>
                                                    <select name="sugar_level" class="form-select form-select-sm">
                                                        <option>0%</option>
                                                        <option>25%</option>
                                                        <option selected>50%</option>
                                                        <option>75%</option>
                                                        <option>100%</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">Ice</label>
                                                    <select name="ice_level" class="form-select form-select-sm">
                                                        <option>No Ice</option>
                                                        <option>Less Ice</option>
                                                        <option selected>Regular Ice</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small">Pickup time</label>
                                                    <input type="datetime-local" name="pickup_at" class="form-control form-control-sm" value="{{ old('pickup_at') }}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small">Notes</label>
                                                    <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Pearls, less sweet, no sinkers...">{{ old('notes') }}</textarea>
                                                </div>
                                                <div class="col-12 d-grid">
                                                    <button type="submit" class="btn btn-cta" @disabled($drink->stock === 0)>Order this drink</button>
                                                </div>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-cta w-100">Login to order</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="card glass-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <p class="mb-0 text-muted">No drinks are available yet.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="col-lg-4">
            <div class="card glass-card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <p class="eyebrow mb-2">How it works</p>
                    <h2 class="section-title mb-3">Customer flow</h2>
                    <ol class="ps-3 mb-0 text-muted">
                        <li>Register or login as a customer.</li>
                        <li>Pick a drink, size, sugar, and ice level.</li>
                        <li>Submit the order and open the receipt page.</li>
                        <li>Check your email log and download the PDF receipt.</li>
                    </ol>
                </div>
            </div>

            @auth
                <div class="card glass-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <p class="eyebrow mb-2">Recent orders</p>
                        <h2 class="section-title mb-3">Your latest activity</h2>
                        @forelse ($recentOrders as $order)
                            <a href="{{ route('orders.show', $order) }}" class="recent-order d-block text-decoration-none">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $order->order_number }}</strong>
                                    <span class="badge rounded-pill text-bg-light">{{ ucfirst($order->status) }}</span>
                                </div>
                                <div class="small text-muted">{{ $order->ordered_at?->format('M d, Y h:i A') }}</div>
                            </a>
                        @empty
                            <p class="text-muted mb-0">You have not placed an order yet.</p>
                        @endforelse
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
