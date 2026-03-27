@extends('layouts.app')
@section('title','Verifikasi Pengajuan')
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Verifikasi Pengajuan</h3>
        <div>
            <a href="{{ route('admin.disbursements') }}" class="btn btn-info">
                <i class="fas fa-money-bill-wave"></i> Kelola Pencairan
            </a>
        </div>
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Judul</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allSub as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td>{{ $s->user_name }}</td>
                                <td>
                                    <div>
                                        <strong>{{ Str::limit($s->title, 30) }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($s->description, 50) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($s->amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($s->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($s->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($s->status == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @elseif($s->status == 'disbursed')
                                        <span class="badge bg-info">Pencairan</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ \Carbon\Carbon::parse($s->created_at)->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($s->status == 'pending')
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    onclick="approveSubmission({{ $s->id }})"
                                                    title="Setujui">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="rejectSubmission({{ $s->id }})"
                                                    title="Tolak">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <br>Tidak ada pengajuan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($allSub->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $allSub->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" 
                                  name="rejection_reason" rows="4" 
                                  placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveSubmission(id) {
    if (confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/submissions/${id}/approve`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectSubmission(id) {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    const form = document.getElementById('rejectForm');
    form.action = `/admin/submissions/${id}/reject`;
    modal.show();
}
</script>

@endsection