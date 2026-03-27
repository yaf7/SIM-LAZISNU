@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-history"></i> Riwayat Donasi</h4>
                    <a href="{{ route('donate.form') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Donasi Baru
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif

                    @php
                        $totalDonations = $donations->where('payment_status', 'paid')->sum('amount');
                        $pendingDonations = $donations->where('payment_status', 'unpaid')->count();
                    @endphp

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5><i class="fas fa-heart"></i> Total Donasi</h5>
                                    <h3>Rp {{ number_format($totalDonations, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5><i class="fas fa-clock"></i> Menunggu Pembayaran</h5>
                                    <h3>{{ $pendingDonations }} Donasi</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Status Pembayaran</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donations as $index => $donation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>Rp {{ number_format($donation->amount, 0, ',', '.') }}</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $donation->status === 'pending' ? 'warning text-dark' : ($donation->status === 'approved' ? 'success' : ($donation->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                                {{ ucfirst($donation->status) }}
                                            </span>
                                            @if($donation->status === 'rejected' && $donation->rejection_reason)
                                                <br>
                                                <small class="text-danger mt-1">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    {{ $donation->rejection_reason }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $donation->payment_status === 'unpaid' ? 'danger' : 'success' }}">
                                                {{ $donation->payment_status === 'unpaid' ? 'Belum Bayar' : 'Sudah Bayar' }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ date('d/m/Y H:i', strtotime($donation->created_at)) }}
                                            @if($donation->paid_at)
                                                <br><small class="text-success">
                                                    Dibayar: {{ date('d/m/Y H:i', strtotime($donation->paid_at)) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($donation->payment_status === 'unpaid' && $donation->status !== 'rejected')
                                                <a href="{{ route('donate.payment', $donation->id) }}"
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-credit-card"></i> Bayar
                                                </a>
                                            @elseif($donation->status === 'rejected')
                                                <span class="text-danger">
                                                    <i class="fas fa-times-circle"></i> Ditolak
                                                </span>
                                            @else
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle"></i> Lunas
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                                            <p>
                                                Belum ada donasi. 
                                                <a href="{{ route('donate.form') }}">Mulai berdonasi sekarang!</a>
                                            </p>
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