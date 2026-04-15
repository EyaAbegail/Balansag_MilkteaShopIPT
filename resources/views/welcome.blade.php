@extends('layouts.app')

@section('content')
<div class="container py-5">
        <div class="card glass-card border-0 shadow-sm welcome-card">
            <div class="card-body p-5 text-center text-md-start">
                <p class="eyebrow mb-2">BILLATEA Shop - Premium Tea Experience</p>
                <h1 class="section-title mb-3">A warmer, more realistic ordering experience for the shop.</h1>
                <p class="text-muted mb-4">Browse the refreshed menu, customize your drink, and move through a flow that feels closer to a real café counter and pickup station.</p>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                    <a href="{{ route('catalog.index') }}" class="btn btn-cta">Go to menu</a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-cta">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-cta">Register</a>
                    @endguest
                </div>
            </div>
        </div>
</div>
@endsection
