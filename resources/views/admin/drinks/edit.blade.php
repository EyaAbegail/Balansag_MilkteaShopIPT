@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card glass-card border-0 shadow-lg">
        <div class="card-body p-4 p-md-5">
            <p class="eyebrow mb-2">Admin panel</p>
            <h1 class="section-title mb-4">Edit {{ $drink->name }}</h1>
            @include('admin.drinks.partials.form', ['action' => route('admin.drinks.update', $drink), 'method' => 'PUT'])
        </div>
    </div>
</div>
@endsection
