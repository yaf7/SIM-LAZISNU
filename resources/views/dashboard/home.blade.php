@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="container">
  <div class="row mb-4">
    <div class="col-md-12">
      <h3>Selamat Datang, {{ auth()->user()->name }}!</h3>
      <p class="text-muted">
        Hari ini {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }}
      </p>
    </div>
  </div>

  {{-- Ringkasan --}}
  <div class="row mb-5">
    <div class="col-md-3 mb-3">
      <div class="card text-white bg-success h-100">
        <div class="card-body">
          <h6>Total Donasi</h6>
          <h4>Rp {{ number_format($totalDonasi, 0, ',', '.') }}</h4>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-white bg-primary h-100">
        <div class="card-body">
          <h6>Total Transaksi</h6>
          <h4>{{ $totalTransaksi }}</h4>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-white bg-warning h-100">
        <div class="card-body">
          <h6>Menunggu Bayar</h6>
          <h4>{{ $pending }}</h4>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-white bg-info h-100">
        <div class="card-body">
          <h6>Sukses Bayar</h6>
          <h4>{{ $paid }}</h4>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabel 5 Donasi Terakhir --}}
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-list"></i> 5 Donasi Terakhir
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Nominal</th>
                  <th>Status</th>
                  <th>Pembayaran</th>
                  <th>Dibuat</th>
                  <th>Dibayar</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentDonations as $donation)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>Rp {{ number_format($donation->amount,0,',','.') }}</td>
                  <td>
                    <span class="badge bg-{{ $donation->status=='pending'?'warning text-dark':'success' }}">
                      {{ ucfirst($donation->status) }}
                    </span>
                  </td>
                  <td>
                    <span class="badge bg-{{ $donation->payment_status=='unpaid'?'danger':'success' }}">
                      {{ $donation->payment_status=='unpaid'?'Belum Bayar':'Sudah Bayar' }}
                    </span>
                  </td>
                  <td>{{ date('d/m/Y H:i', strtotime($donation->created_at)) }}</td>
                  <td>
                    {{ $donation->paid_at
                      ? date('d/m/Y H:i', strtotime($donation->paid_at))
                      : '-' }}
                  </td>
                  <td>
                    @if($donation->payment_status=='unpaid')
                      <a href="{{ route('donate.payment',$donation->id) }}"
                         class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-credit-card"></i>
                      </a>
                    @else
                      <i class="fas fa-check text-success"></i>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center py-4">
                    Belum ada donasi. 
                    <a href="{{ route('donate.form') }}">Donasi sekarang!</a>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
