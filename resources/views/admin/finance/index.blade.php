@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Total Pemasukan</h6>
                            <h4 class="mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Total Pengeluaran</h6>
                            <h4 class="mb-0">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h4>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12 mb-3">
            <div class="card {{ $balance >= 0 ? 'bg-primary' : 'bg-warning' }} text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Saldo</h6>
                            <h4 class="mb-0">Rp {{ number_format($balance, 0, ',', '.') }}</h4>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Bulanan -->
    @if(count($monthlyStats) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Statistik Pemasukan 12 Bulan Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Total Pemasukan</th>
                                    <th>Jumlah Donasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyStats as $stat)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $stat->month)->format('F Y') }}</td>
                                    <td>Rp {{ number_format($stat->total_income, 0, ',', '.') }}</td>
                                    <td>{{ $stat->count_donations }} donasi</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Pemasukan dan Pengeluaran -->
    <div class="row">
        <!-- Pemasukan -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-arrow-down-circle me-2"></i>Data Pemasukan
                    </h5>
                    <small>Donasi yang sudah completed</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Donatur</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incomeData as $income)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded bg-success text-white me-2 d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr($income->user_name ?? 'A', 0, 1)) }}
                                            </div>
                                            <small>{{ $income->user_name ?? 'Anonim' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            Rp {{ number_format($income->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($income->paid_at)->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        Belum ada pemasukan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($incomeData->hasPages())
                    <div class="card-footer">
                        {{ $incomeData->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-arrow-up-circle me-2"></i>Data Pengeluaran
                    </h5>
                    <small>Pengajuan yang sudah disbursed</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Pengaju</th>
                                    <th>Judul</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenseData as $expense)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded bg-danger text-white me-2 d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr($expense->user_name ?? 'A', 0, 1)) }}
                                            </div>
                                            <small>{{ $expense->user_name ?? 'Unknown' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-truncate d-block" style="max-width: 120px;">
                                            {{ $expense->title }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-danger">
                                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($expense->approved_at)->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Belum ada pengeluaran
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($expenseData->hasPages())
                    <div class="card-footer">
                        {{ $expenseData->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul me-2"></i>Ringkasan Keuangan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="border-end pe-3">
                                <h6 class="text-success">Total Donasi Completed</h6>
                                <p class="mb-0">{{ DB::table('donations')->where('status', 'completed')->count() }} transaksi</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-end pe-3">
                                <h6 class="text-danger">Total Pengajuan Disbursed</h6>
                                <p class="mb-0">{{ DB::table('submissions')->where('status', 'disbursed')->count() }} transaksi</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-primary">Rata-rata Donasi</h6>
                            <p class="mb-0">
                                Rp {{ number_format($totalIncome > 0 && DB::table('donations')->where('status', 'completed')->count() > 0 ? $totalIncome / DB::table('donations')->where('status', 'completed')->count() : 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: 600;
    min-width: 32px;
}

.card-body .table td {
    vertical-align: middle;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endsection