@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card password-reset-card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="bi bi-lock me-2"></i>
                    Reset Password
                </h4>
                <p class="mb-0 mt-2 opacity-75">Enter your new password below</p>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-key-fill text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <p class="text-muted">
                        Resetting password for<br>
                        <strong class="text-primary">{{ $email }}</strong>
                    </p>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock-fill me-1"></i>New Password
                        </label>
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            id="password" 
                            name="password" 
                            required 
                            placeholder="Enter new password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock me-1"></i>Confirm Password
                        </label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required 
                            placeholder="Repeat new password">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Reset Password
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="bi bi-box-arrow-in-left me-1"></i>Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.password-reset-card .card-body {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(108, 117, 125, 0.05) 100%);
}
</style>
@endsection
