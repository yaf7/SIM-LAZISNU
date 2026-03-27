@extends('layouts.app')
@section('title','Detail Pengajuan')
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Detail Pengajuan</h3>
        <a href="{{ route('submissions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
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

    <div class="row">
        {{-- Detail Pengajuan --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $submission->title }}</h5>
                    <span class="badge fs-6 bg-{{ 
                        $submission->status == 'pending' ? 'warning' : 
                        ($submission->status == 'approved' ? 'success' : 
                        ($submission->status == 'rejected' ? 'danger' : 'info')) }}">
                        {{ ucfirst($submission->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3"><strong>Total Pengajuan:</strong><br>
                        <span class="fs-5 text-primary">Rp {{ number_format($submission->amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="mb-3"><strong>Tanggal Pengajuan:</strong><br>
                        {{ \Carbon\Carbon::parse($submission->created_at)->format('d M Y, H:i') }}
                    </div>

                    @if($submission->approved_at)
                        <div class="mb-3"><strong>Tanggal Disetujui:</strong><br>
                            {{ \Carbon\Carbon::parse($submission->approved_at)->format('d M Y, H:i') }}
                        </div>
                    @endif

                    <div class="mb-3"><strong>Deskripsi:</strong><br>{{ $submission->description }}</div>

                    @if($submission->status == 'rejected' && $submission->rejection_reason)
                        <div class="alert alert-danger">
                            <strong>Alasan Penolakan:</strong><br>{{ $submission->rejection_reason }}
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    @if($submission->status == 'approved' && !$disbursement)
                        <a href="{{ route('submissions.disbursement.create', $submission->id) }}" class="btn btn-success">
                            <i class="fas fa-money-bill-wave"></i> Atur Pencairan
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Informasi Pencairan --}}
        <div class="col-md-4">
            @if($disbursement)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Informasi Pencairan</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Metode:</strong>
                            @if($disbursement->method == 'bank_transfer')
                                Transfer Bank
                            @elseif($disbursement->method == 'e_wallet')
                                E-Wallet
                            @else
                                Tunai
                            @endif
                        </p>

                        <p><strong>Nama Penerima:</strong> {{ $disbursement->account_name }}</p>
                        <p><strong>Nomor Rekening/HP:</strong> {{ $disbursement->account_number }}</p>

                        @if($disbursement->bank_name)
                            <p><strong>Bank:</strong> {{ $disbursement->bank_name }}</p>
                        @endif

                        @if($disbursement->ewallet_type)
                            <p><strong>Jenis E-Wallet:</strong> {{ strtoupper($disbursement->ewallet_type) }}</p>
                        @endif

                        <p><strong>Status:</strong>
                            <span class="badge bg-{{ 
                                $disbursement->status == 'pending' ? 'warning' : 
                                ($disbursement->status == 'processing' ? 'info' : 
                                ($disbursement->status == 'completed' ? 'success' : 'danger')) }}">
                                {{ ucfirst($disbursement->status) }}
                            </span>
                        </p>

                        @if($disbursement->notes)
                            <p><strong>Catatan:</strong> {{ $disbursement->notes }}</p>
                        @endif

                        <p><strong>Diajukan Pada:</strong><br>
                            {{ \Carbon\Carbon::parse($disbursement->created_at)->format('d M Y, H:i') }}
                        </p>

                        @if($disbursement->processed_at)
                            <p><strong>Diproses Pada:</strong><br>
                                {{ \Carbon\Carbon::parse($disbursement->processed_at)->format('d M Y, H:i') }}
                            </p>
                        @endif
                    </div>

                    {{-- Admin: Update status --}}
                    @if(auth()->user()->is_admin && in_array($disbursement->status, ['pending', 'processing']))
                        <div class="card-footer text-end">
                            <form method="POST" action="{{ route('admin.disbursements.updateStatus', $disbursement->id) }}">
                                @csrf
                                <div class="input-group">
                                    <select name="status" class="form-select" required>
                                        <option value="">-- Update Status --</option>
                                        <option value="processing">Diproses</option>
                                        <option value="completed">Selesai</option>
                                        <option value="failed">Gagal</option>
                                    </select>
                                    <button class="btn btn-outline-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
