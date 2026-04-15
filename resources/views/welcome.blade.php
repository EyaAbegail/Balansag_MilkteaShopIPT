@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card glass-card border-0 shadow-sm">
        <div class="card-body p-5 text-center">
            <h1 class="section-title mb-3">Milktea Shop</h1>
            <p class="text-muted mb-4">Open the menu to browse drinks and place an order.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-cta">Go to menu</a>
        </div>
    </div>
</div>
@endsection
