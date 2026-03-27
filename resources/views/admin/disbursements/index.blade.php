@extends('layouts.app')
@section('title','Kelola Pencairan')
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Kelola Pencairan</h3>
        <div>
            <a href="{{ route('admin.submissions') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Pengajuan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped"> 
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Pengajuan</th>
                            <th>Metode</th>
                            <th>Detail</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disbursements as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->user_name }}</td>
                                <td>
                                    <strong>{{ Str::limit($d->submission_title, 25) }}</strong>
                                </td>
                                <td>
                                    @if($d->method == 'bank_transfer')
                                        <i class="fas fa-university"></i> Bank Transfer
                                    @elseif($d->method == 'e_wallet')
                                        <i class="fas fa-mobile-alt"></i> E-Wallet
                                    @else
                                        <i class="fas fa-money-bill"></i> Tunai
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        <strong>{{ $d->account_name }}</strong><br>
                                        {{ $d->account_number }}
                                        @if($d->bank_name)
                                            <br>{{ $d->bank_name }}
                                        @endif
                                        @if($d->ewallet_type)
                                            <br>{{ $d->ewallet_type }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($d->submission_amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($d->status == 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($d->status == 'processing')
                                        <span class="badge bg-info">Diproses</span>
                                    @elseif($d->status == 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($d->status == 'failed')
                                        <span class="badge bg-danger">Gagal</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($d->status == 'pending')
                                            <button type="button" class="btn btn-info btn-sm" 
                                                    onclick="processDisbursement({{ $d->id }})"
                                                    title="Proses">
                                                <i class="fas fa-play"></i> Proses
                                            </button>
                                        @endif
                                        
                                        @if($d->status == 'processing')
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    onclick="completeDisbursement({{ $d->id }})"
                                                    title="Selesaikan">
                                                <i class="fas fa-check"></i> Selesai
                                            </button>
                                        @endif
                                        
                                        @if(in_array($d->status, ['pending', 'processing']))
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="failDisbursement({{ $d->id }})"
                                                    title="Gagalkan">
                                                <i class="fas fa-times"></i> Gagal
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <br>Tidak ada data pencairan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($disbursements->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $disbursements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Fail Disbursement -->
<div class="modal fade" id="failModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gagalkan Pencairan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="failForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="failure_reason" class="form-label">Alasan Kegagalan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="failure_reason" 
                                  name="failure_reason" rows="4" 
                                  placeholder="Jelaskan alasan kegagalan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Gagalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function processDisbursement(id) {
    if (confirm('Mulai proses pencairan ini?')) {
        submitAction(`/admin/disbursements/${id}/process`);
    }
}

function completeDisbursement(id) {
    if (confirm('Tandai pencairan ini sebagai selesai?')) {
        submitAction(`/admin/disbursements/${id}/complete`);
    }
}

function failDisbursement(id) {
    const modal = new bootstrap.Modal(document.getElementById('failModal'));
    const form = document.getElementById('failForm');
    form.action = `/admin/disbursements/${id}/fail`;
    modal.show();
}

function submitAction(url) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    form.appendChild(csrfToken);
    document.body.appendChild(form);
    form.submit();
}
</script>

@endsection