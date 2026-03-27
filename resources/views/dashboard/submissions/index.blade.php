@extends('layouts.app')
@section('title','Pengajuan Saya')
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Pengajuan Saya</h3>
        <a href="{{ route('submissions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajukan Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($subs->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada pengajuan</h5>
                <p class="text-muted">Mulai dengan membuat pengajuan baru</p>
                <a href="{{ route('submissions.create') }}" class="btn btn-primary">Buat Pengajuan</a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($subs as $s)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title">{{ $s->title }}</h6>
                                @if($s->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($s->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($s->status == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($s->status == 'disbursed')
                                    <span class="badge bg-info">Pencairan</span>
                                @endif
                            </div>
                            
                            <p class="card-text">
                                <strong>Total:</strong> Rp {{ number_format($s->amount, 0, ',', '.') }}<br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($s->created_at)->format('d M Y, H:i') }}</small>
                            </p>
                            
                            <p class="card-text">
                                {{ Str::limit($s->description, 100) }}
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('submissions.show', $s->id) }}" class="btn btn-outline-primary btn-sm">
                                Lihat Detail
                            </a>
                            
                            @if($s->status == 'approved')
                                <a href="{{ route('submissions.disbursement.create', $s->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-money-bill-wave"></i> Cairkan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection