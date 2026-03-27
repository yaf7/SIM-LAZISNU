@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus me-2"></i>
                    Create Account
                </h4>
                <p class="mb-0 mt-2 opacity-75">Join us today</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1"></i>Full Name
                        </label>
                        <input type="text" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               value="{{ old('name') }}"
                               placeholder="Enter your full name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                               required>
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
                                   placeholder="Create a password"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <small><i class="bi bi-info-circle me-1"></i>Password must be at least 8 characters long</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill me-1"></i>Confirm Password
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   id="password_confirmation"
                                   placeholder="Confirm your password"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                <i class="bi bi-eye" id="toggleIconConfirm"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Optional role selector --}}
                    {{-- 
                    <div class="mb-4">
                        <label for="role" class="form-label">
                            <i class="bi bi-person-badge me-1"></i>Register as
                        </label>
                        <select name="role" class="form-select" id="role">
                            <option value="customer">Customer</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    --}}

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-person-plus me-2"></i>
                            Create Account
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-0 text-muted">Already have an account?</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary mt-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
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

document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
    const passwordField = document.getElementById('password_confirmation');
    const toggleIcon = document.getElementById('toggleIconConfirm');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'bi bi-eye';
    }
});

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strength = checkPasswordStrength(password);
    // You can add visual feedback for password strength here
});

function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
}
</script>
@endsection