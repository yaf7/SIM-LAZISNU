@extends('layouts.app')
@section('title', 'Verify Reset Code')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card password-reset-card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Verify Code
                </h4>
                <p class="mb-0 mt-2 opacity-75">Enter the verification code</p>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-envelope-check text-success" style="font-size: 2rem;"></i>
                    </div>
                    <p class="text-muted">
                        We've sent a 6-digit verification code to<br>
                        <strong class="text-primary">{{ $email }}</strong>
                    </p>
                    <p class="text-muted small">
                        Check your email and enter the code below
                    </p>
                </div>

                <form method="POST" action="{{ route('password.verify') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <div class="mb-4">
                        <label for="token" class="form-label text-center d-block">
                            <i class="bi bi-key me-1"></i>Verification Code
                        </label>
                        <input type="text" 
                               name="token" 
                               class="form-control form-control-lg text-center @error('token') is-invalid @enderror" 
                               id="token" 
                               maxlength="6" 
                               placeholder="000000"
                               style="font-size: 1.5rem; letter-spacing: 0.5rem; font-weight: bold;"
                               required 
                               autofocus>
                        @error('token')
                            <div class="invalid-feedback text-center">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-center">
                            <small><i class="bi bi-clock me-1"></i>Code expires in 15 minutes</small>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>
                            Verify Code
                        </button>
                    </div>
                </form>

                <div class="text-center mb-3">
                    <p class="text-muted small mb-2">Didn't receive the code?</p>
                    <button class="btn btn-outline-secondary btn-sm" onclick="resendCode()" id="resendBtn">
                        <i class="bi bi-arrow-clockwise me-1"></i>Resend Code
                    </button>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Try Different Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-format code input
document.getElementById('token').addEventListener('input', function(e) {
    let value = e.target.value;
    if (value.length > 6) {
        value = value.substr(0, 6);
    }
    e.target.value = value;
});

// Auto-submit when 6 digits entered
document.getElementById('token').addEventListener('input', function(e) {
    if (e.target.value.length === 6) {
        // Optional: auto-submit form
        // e.target.form.submit();
    }
});

// Resend code functionality
let resendTimer;
let resendCount = 0;

function resendCode() {
    const resendBtn = document.getElementById('resendBtn');
    resendBtn.disabled = true;
    
    // Start countdown
    let timeLeft = 60;
    resendBtn.innerHTML = `<i class="bi bi-clock me-1"></i>Resend in ${timeLeft}s`;
    
    resendTimer = setInterval(() => {
        timeLeft--;
        resendBtn.innerHTML = `<i class="bi bi-clock me-1"></i>Resend in ${timeLeft}s`;
        
        if (timeLeft <= 0) {
            clearInterval(resendTimer);
            resendBtn.disabled = false;
            resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Resend Code';
        }
    }, 1000);
    
    // Here you would make an AJAX call to resend the code
    // For now, just show a success message
    setTimeout(() => {
        // Show success message (you can implement this with a toast or alert)
        console.log('Code resent successfully');
    }, 1000);
}

// Focus on input when page loads
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('token').focus();
});
</script>

<style>
.password-reset-card .card-body {
    background: linear-gradient(135deg, rgba(25, 135, 84, 0.05) 0%, rgba(108, 117, 125, 0.05) 100%);
}

#token::placeholder {
    color: #dee2e6 !important;
    opacity: 0.7;
}
</style>
@endsection