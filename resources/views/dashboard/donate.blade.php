@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-heart text-danger"></i> Form Donasi</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('donate.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <h5>Pilih Nominal Donasi:</h5>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-block amount-btn" data-amount="10000">
                                        Rp 10.000
                                    </button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-block amount-btn" data-amount="25000">
                                        Rp 25.000
                                    </button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-block amount-btn" data-amount="50000">
                                        Rp 50.000
                                    </button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-block amount-btn" data-amount="100000">
                                        Rp 100.000
                                    </button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-block amount-btn" data-amount="250000">
                                        Rp 250.000
                                    </button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-block amount-btn" data-amount="500000">
                                        Rp 500.000
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="amount">Atau Masukkan Nominal Lain:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" 
                                       name="amount" 
                                       placeholder="Minimal Rp 1.000"
                                       min="1000"
                                       value="{{ old('amount') }}">
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Minimal donasi Rp 1.000</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-heart"></i> Donasi Sekarang
                            </button>
                        </div>
                    </form>

                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history"></i> Lihat Riwayat Donasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountButtons = document.querySelectorAll('.amount-btn');
    const amountInput = document.getElementById('amount');
    
    amountButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            amountButtons.forEach(btn => btn.classList.remove('btn-primary'));
            amountButtons.forEach(btn => btn.classList.add('btn-outline-primary'));
            
            // Add active class to clicked button
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
            
            // Set amount value
            const amount = this.getAttribute('data-amount');
            amountInput.value = amount;
        });
    });
    
    // Reset button styles when input is manually changed
    amountInput.addEventListener('input', function() {
        amountButtons.forEach(btn => btn.classList.remove('btn-primary'));
        amountButtons.forEach(btn => btn.classList.add('btn-outline-primary'));
    });
});
</script>
@endsection