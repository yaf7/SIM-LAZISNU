@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-credit-card"></i> Pembayaran Donasi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Detail Donasi:</h5>
                            <p><strong>Jumlah Donasi:</strong> Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge badge-warning">{{ ucfirst($donation->status) }}</span>
                            </p>
                            <p><strong>Tanggal:</strong> {{ date('d/m/Y H:i', strtotime($donation->created_at)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Metode Pembayaran:</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-university text-primary"></i> Transfer Bank</li>
                                <li><i class="fas fa-mobile-alt text-success"></i> E-Wallet (OVO, DANA, GoPay)</li>
                                <li><i class="fas fa-credit-card text-info"></i> Kartu Kredit/Debit</li>
                                <li><i class="fas fa-store text-warning"></i> Retail Outlet (Alfamart, Indomaret)</li>
                            </ul>
                        </div>
                    </div>
                    
                    <hr>
                    
                    @if($donation->payment_status === 'unpaid')
                        <div class="text-center">
                            <p class="text-muted mb-3">Klik tombol di bawah untuk melanjutkan pembayaran</p>
                            <button id="pay-button" class="btn btn-success btn-lg">
                                <i class="fas fa-heart"></i> Bayar Donasi - Rp {{ number_format($donation->amount, 0, ',', '.') }}
                            </button>
                        </div>
                        
                        <div id="loading" class="text-center" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2">Memproses pembayaran...</p>
                        </div>
                    @else
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h5>Terima kasih atas donasi Anda!</h5>
                            <p>Pembayaran telah berhasil diproses pada {{ date('d/m/Y H:i', strtotime($donation->paid_at)) }}</p>
                        </div>
                    @endif
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('donate.form') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> Donasi Lagi
                        </a>
                        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history"></i> Riwayat Donasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const payButton = document.getElementById('pay-button');
  const loading   = document.getElementById('loading');
  const url       = '{{ route("donate.create-invoice", $donation->id) }}';
  const token     = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  payButton.addEventListener('click', async () => {
    payButton.style.display = 'none';
    loading.style.display   = 'block';
    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type'  : 'application/json',
          'X-CSRF-TOKEN'  : token
        },
        body: JSON.stringify({})
      });
      const data = await res.json();
      if (!res.ok) throw data;
      window.location.href = data.invoice_url;
    } catch (err) {
      console.error(err);
      alert('Gagal: ' + (err.error || 'Unknown error'));
      payButton.style.display = 'block';
      loading.style.display   = 'none';
    }
  });
});
</script>

@endsection