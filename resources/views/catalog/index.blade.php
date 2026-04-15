@extends('layouts.app')

@section('content')
@php
    $allDrinks = $categories->flatMap->drinks;
    $availableDrinks = $allDrinks->where('stock', '>', 0);
    $signatureCount = $featuredDrinks->count();
    $averagePrice = $allDrinks->count() ? $allDrinks->avg(fn ($drink) => (float) $drink->price) : 0;
@endphp
<section class="hero-section">
    <div class="container py-5">
        <div class="hero-panel">
            <div class="row align-items-center g-4 g-xl-5">
                <div class="col-lg-7">
                    <p class="eyebrow mb-3">House special milk tea bar</p>
                    <h1 class="display-4 fw-bold mb-3">Brewed like a café, loved like your daily milk tea.</h1>
                    <p class="lead text-muted mb-4">Discover chewy pearl classics, fruit teas, and creamy signatures prepared for fast pickup. Customize sweetness, ice, and size in a storefront that feels like an actual shop menu.</p>
                    <div class="hero-actions d-flex flex-wrap gap-3 mb-4">
                        <a href="#menu-grid" class="btn btn-cta px-4">Browse the menu</a>
                        @guest
                            <div class="guest-auth-actions d-flex flex-wrap gap-2">
                                <a href="{{ route('login') }}" class="btn btn-outline-cta px-4">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-cta px-4">Register</a>
                            </div>
                        @else
                            <a href="{{ route('home') }}" class="btn btn-outline-dark px-4">Open dashboard</a>
                        @endguest
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="badge badge-soft">Fresh pearls daily</span>
                        <span class="badge badge-soft">Pickup-ready ordering</span>
                        <span class="badge badge-soft">Email receipt + PDF</span>
                        <span class="badge badge-soft">Admin reporting tools</span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-showcase shadow-lg">
                        <img src="{{ $featuredDrinks->first()?->image_url ?? asset('pics/Wintermelon-Milk-Tea.jpg') }}" alt="BILLATEA Milk Tea Selection" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0">
                        <div class="hero-showcase__card">
                            <p class="eyebrow mb-2">Today’s highlights</p>
                            <h2 class="h3 mb-3">Best-selling blends</h2>
                            @forelse ($featuredDrinks as $drink)
                                @if ($loop->first)
                                    @php $scrollingHighlights = $featuredDrinks->count() > 1; @endphp
                                    <div class="featured-scroll{{ $scrollingHighlights ? ' is-animated' : '' }}">
                                        <div class="featured-scroll__track">
                                @endif
                                            <div class="featured-item">
                                                <img src="{{ $drink->image_url }}" alt="{{ $drink->name }}" class="featured-item__image">
                                                <div class="featured-item__copy">
                                                    <strong>{{ $drink->name }}</strong>
                                                    <div class="small text-muted">{{ $drink->category->name ?? 'Signature' }} · Freshly made</div>
                                                </div>
                                                <span class="fw-semibold">PHP {{ number_format($drink->price, 2) }}</span>
                                            </div>
                                @if ($loop->last && $scrollingHighlights)
                                    @foreach ($featuredDrinks as $duplicateDrink)
                                        <div class="featured-item featured-item--clone" aria-hidden="true">
                                            <img src="{{ $duplicateDrink->image_url }}" alt="" class="featured-item__image">
                                            <div class="featured-item__copy">
                                                <strong>{{ $duplicateDrink->name }}</strong>
                                                <div class="small text-muted">{{ $duplicateDrink->category->name ?? 'Signature' }} · Freshly made</div>
                                            </div>
                                            <span class="fw-semibold">PHP {{ number_format($duplicateDrink->price, 2) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                                @if ($loop->last)
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <p class="text-muted mb-0">No featured drinks yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card h-100">
                    <span class="metric-label">Available drinks</span>
                    <strong>{{ $availableDrinks->count() }}</strong>
                    <p class="metric-copy mb-0">Ready to order across your active menu.</p>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card h-100">
                    <span class="metric-label">Signature picks</span>
                    <strong>{{ $signatureCount }}</strong>
                    <p class="metric-copy mb-0">Featured blends highlighted by the shop.</p>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card h-100">
                    <span class="metric-label">Average starting price</span>
                    <strong>PHP {{ number_format($averagePrice, 0) }}</strong>
                    <p class="metric-copy mb-0">Friendly price point for everyday drinks.</p>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card h-100">
                    <span class="metric-label">Pickup cadence</span>
                    <strong>12 min</strong>
                    <p class="metric-copy mb-0">Typical prep time for handcrafted orders.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container pb-5" id="menu-grid">
    <div class="row g-4">
        <div class="col-lg-8">
            @forelse ($categories as $category)
                <section class="menu-block mb-4">
                    <div class="category-header d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-3">
                        <div>
                            <p class="eyebrow mb-1">{{ $category->name }}</p>
                            <h2 class="section-title mb-1">{{ $category->description ?: $category->name }}</h2>
                            <p class="text-muted mb-0">Balanced sweetness, creamy texture, and café-style presentation.</p>
                        </div>
                        <span class="category-count">{{ $category->drinks->count() }} drinks</span>
                    </div>

                    <div class="row g-4">
                        @foreach ($category->drinks as $drink)
                            @php
                                $isPopular = $drink->is_featured || $loop->first;
                                $stockLevel = $drink->stock > 10 ? 'In stock' : ($drink->stock > 0 ? 'Limited batch' : 'Sold out');
                                $prepTime = $drink->is_featured ? '8-10 min' : '10-12 min';
                            @endphp
                            <div class="col-md-6">
                                <div class="card menu-card h-100 border-0 shadow-sm">
                                    <div class="menu-card__media">
                                        <img src="{{ $drink->image_url }}" alt="{{ $drink->name }}" class="menu-image">
                                        <div class="menu-card__badges">
                                            @if ($isPopular)
                                                <span class="menu-badge">Popular</span>
                                            @endif
                                            <span class="menu-badge menu-badge--light">{{ $stockLevel }}</span>
                                        </div>
                                    </div>

                                    <div class="card-body menu-card__body">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                            <div>
                                                <h3 class="h5 mb-1 menu-card__title">{{ $drink->name }}</h3>
                                                <p class="text-muted small mb-0">{{ $drink->description }}</p>
                                            </div>
                                            <span class="price-tag">PHP {{ number_format($drink->price, 2) }}</span>
                                        </div>

                                        <div class="menu-card__footer-copy">
                                            <span>{{ $category->name }}</span>
                                            <span>Freshly made to order</span>
                                        </div>

                                        <div class="menu-meta">
                                            <span>Prep {{ $prepTime }}</span>
                                            <span>{{ $drink->stock }} cups left</span>
                                            <span>4.8 ★</span>
                                        </div>

                                        @auth
                                            <button type="button" class="btn btn-cta w-100" data-bs-toggle="modal" data-bs-target="#customizeModal-{{ $drink->id }}" @disabled($drink->stock === 0)>
                                                Customize & Order
                                            </button>
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
            <div class="card glass-card border-0 shadow-sm mb-4 side-panel">
                <div class="card-body p-4">
                    <p class="eyebrow mb-2">Store experience</p>
                    <h2 class="section-title mb-3">Order in minutes</h2>
                    <div class="flow-list">
                        <div class="flow-item">
                            <span class="flow-step">1</span>
                            <div>
                                <strong>Choose your base drink</strong>
                                <div class="text-muted small">Classic milk tea, fruit infusions, or rich house signatures.</div>
                            </div>
                        </div>
                        <div class="flow-item">
                            <span class="flow-step">2</span>
                            <div>
                                <strong>Adjust the details</strong>
                                <div class="text-muted small">Set size, sugar, ice, and special notes before checkout.</div>
                            </div>
                        </div>
                        <div class="flow-item">
                            <span class="flow-step">3</span>
                            <div>
                                <strong>Pick up with confidence</strong>
                                <div class="text-muted small">Track your receipt, reopen it later, and download the PDF anytime.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card glass-card border-0 shadow-sm mb-4 side-panel side-panel--accent">
                <div class="card-body p-4">
                    <p class="eyebrow mb-2">What makes it feel real</p>
                    <h2 class="section-title mb-3">Front-of-house details</h2>
                    <ul class="list-unstyled mb-0 text-muted support-list">
                        <li>Visible stock per drink to mirror real counter availability.</li>
                        <li>Featured items and prep-time hints for faster ordering decisions.</li>
                        <li>Branded receipt flow with QR access and downloadable PDF copy.</li>
                    </ul>
                </div>
            </div>

            @auth
                <div class="card glass-card border-0 shadow-sm side-panel">
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

<!-- Customization Modals -->
@auth
    @php $allDrinks = $categories->flatMap->drinks; @endphp
    @foreach ($allDrinks as $drink)
        <div class="modal fade" id="customizeModal-{{ $drink->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-bottom-0 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold">Customize {{ $drink->name }}</h5>
                            <p class="text-muted small mb-0">{{ $drink->description }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.store') }}" method="POST" id="orderForm-{{ $drink->id }}">
                            @csrf
                            <input type="hidden" name="drink_id" value="{{ $drink->id }}">
                            <input type="hidden" name="customer_name" value="{{ auth()->user()->name }}">
                            <input type="hidden" name="customer_email" value="{{ auth()->user()->email }}">
                            <input type="hidden" name="customer_phone" value="{{ auth()->user()->phone }}">

                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Size</label>
                                    <select name="size" class="form-select" required>
                                        <option value="">Select size</option>
                                        <option value="Regular" selected>Regular</option>
                                        <option value="Large">Large</option>
                                        <option value="Jumbo">Jumbo</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" min="1" max="20" value="1" required>
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Sugar Level</label>
                                    <select name="sugar_level" class="form-select" required>
                                        <option value="0%">No Sugar (0%)</option>
                                        <option value="25%">Light Sweet (25%)</option>
                                        <option value="50%" selected>Normal Sweet (50%)</option>
                                        <option value="75%">Sweet (75%)</option>
                                        <option value="100%">Extra Sweet (100%)</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Ice Level</label>
                                    <select name="ice_level" class="form-select" required>
                                        <option value="No Ice">No Ice</option>
                                        <option value="Less Ice">Less Ice</option>
                                        <option value="Regular Ice" selected>Regular Ice</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Pickup Time</label>
                                    <input type="datetime-local" name="pickup_at" class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Special Notes</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="Pearls, less sweet, no sinkers, extra toppings..."></textarea>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex gap-2 justify-content-between align-items-center p-3 rounded" style="background: rgba(212, 117, 138, 0.08);">
                                        <div>
                                            <span class="text-muted small">Total:</span><br>
                                            <strong>PHP {{ number_format($drink->price, 2) }}</strong>
                                        </div>
                                        <button type="submit" class="btn btn-cta px-4" @disabled($drink->stock === 0)>
                                            Place Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endauth
@endsection
