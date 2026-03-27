@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Welcome Back
                </h4>
                <p class="mb-0 mt-2 opacity-75">Sign in to your account</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email Address
                        </label>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               value="{{ old('email') }}"
                               placeholder="Enter your email"
                               required 
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>Password
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password"
                                   placeholder="Enter your password"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                        <i class="bi bi-question-circle me-1"></i>Forgot your password?
                    </a>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-0 text-muted">Don't have an account?</p>
                    <a href="{{ route('register') }}" class="btn btn-outline-success mt-2">
                        <i class="bi bi-person-plus me-2"></i>
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'bi bi-eye';
    }
});
</script>
@endsection