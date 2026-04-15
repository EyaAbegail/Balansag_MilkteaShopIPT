@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card glass-card border-0 shadow-sm welcome-card">
        <div class="card-body p-5 text-center text-md-start">
            <p class="eyebrow mb-2">BILLATEA Shop - Premium Tea Experience</p>
            <h1 class="section-title mb-3">A warmer, more realistic ordering experience for the shop.</h1>
            <p class="text-muted mb-4">Browse the refreshed menu, customize your drink, and move through a flow that feels closer to a real café counter and pickup station.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-cta">Go to menu</a>
        </div>
    </div>
</div>
@endsection
