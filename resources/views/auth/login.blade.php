@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card glass-card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <p class="eyebrow mb-2">Welcome back</p>
                    <h1 class="section-title mb-3">Sign in to the Milktea Shop</h1>
                    <p class="text-muted mb-4">Use the seeded demo accounts if you want to explore quickly: admin@milktea.test, staff@milktea.test, or customer@milktea.test with password password.</p>
                    <div class="guest-auth-actions d-inline-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('login') }}" class="btn btn-outline-cta active">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-cta">Register</a>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="small link-accent" href="{{ route('password.request') }}">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-cta w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
