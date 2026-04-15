@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card glass-card border-0 shadow-lg">
        <div class="card-body p-4 p-md-5">
            <p class="eyebrow mb-2">Admin panel</p>
            <h1 class="section-title mb-4">Add a new drink</h1>
            @include('admin.drinks.partials.form', ['action' => route('admin.drinks.store'), 'method' => 'POST'])
        </div>
    </div>
</div>
@endsection
