@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card password-reset-card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="bi bi-key me-2"></i>
                    Forgot Password?
                </h4>
                <p class="mb-0 mt-2 opacity-75">No worries, we'll help you reset it</p>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-envelope-paper text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <p class="text-muted">
                        Enter your email address and we'll send you a verification code to reset your password.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email Address
                        </label>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter your registered email"
                               required 
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <small><i class="bi bi-info-circle me-1"></i>We'll send a 6-digit verification code</small>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send me-2"></i>
                            Send Reset Code
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Back to Login
                    </a>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-0 text-muted small">
                        Remember your password? 
                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.password-reset-card .card-body {
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(108, 117, 125, 0.05) 100%);
}
</style>
@endsection