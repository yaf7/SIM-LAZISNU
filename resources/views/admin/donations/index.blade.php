@extends('layouts.app')

@section('title', 'Kelola Donasi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Semua Donasi</h3>
                </div>
                <div class="card-body">
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

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allDonasi as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $d->user_name ?? 'User tidak ditemukan' }}</td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            Rp{{ number_format($d->amount, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @switch($d->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-primary">Completed</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($d->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($d->status == 'completed')
                                            <div class="btn-group" role="group">
                                                <!-- Tombol Approve -->
                                                <form action="{{ route('admin.donations.approve', $d->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm('Yakin ingin menyetujui donasi ini?')">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>

                                                <!-- Tombol Reject -->
                                                <button type="button" class="btn btn-danger btn-sm reject-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $d->id }}"
                                                        data-donation-id="{{ $d->id }}">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </div>
                                        @elseif($d->status == 'approved')
                                            <span class="text-success"><i class="fas fa-check-circle"></i> Sudah Disetujui</span>
                                        @elseif($d->status == 'rejected')
                                            <span class="text-danger"><i class="fas fa-times-circle"></i> Ditolak</span>
                                            @if($d->rejection_reason)
                                                <br><small class="text-muted">Alasan: {{ $d->rejection_reason }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Belum dapat diproses</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <br>Belum ada data donasi
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($allDonasi->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $allDonasi->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Reject Donation -->
@foreach($allDonasi as $d)
    @if($d->status == 'completed')
        <div class="modal fade" id="rejectModal{{ $d->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $d->id }}" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.donations.reject', $d->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel{{ $d->id }}">Tolak Donasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Anda yakin ingin menolak donasi ini?
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Donatur:</strong></div>
                                <div class="col-sm-8">{{ $d->user_name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Jumlah:</strong></div>
                                <div class="col-sm-8">Rp{{ number_format($d->amount, 2, ',', '.') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Tanggal:</strong></div>
                                <div class="col-sm-8">{{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y H:i') }}</div>
                            </div>

                            <div class="mb-3">
                                <label for="rejection_reason{{ $d->id }}" class="form-label">
                                    Alasan Penolakan <span class="text-danger">*</span>
                                </label>
                                <textarea 
                                    class="form-control" 
                                    id="rejection_reason{{ $d->id }}" 
                                    name="rejection_reason" 
                                    rows="4" 
                                    maxlength="500" 
                                    required 
                                    placeholder="Masukkan alasan penolakan donasi..."
                                ></textarea>
                                <div class="form-text">Maksimal 500 karakter</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Tolak Donasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

<style>
/* Fix untuk modal backdrop yang stuck */
.modal-backdrop {
    position: fixed !important;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: #000;
}

.modal-backdrop.show {
    opacity: 0.5;
}

/* Pastikan modal muncul di atas backdrop */
.modal {
    z-index: 1050;
}

/* Reset body styling ketika modal tertutup */
body:not(.modal-open) {
    overflow: auto !important;
    padding-right: 0 !important;
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('alert-success') || alert.classList.contains('alert-danger')) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);

    // Fix modal backdrop issue
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modalElement) {
        modalElement.addEventListener('show.bs.modal', function() {
            // Clear any previous input when modal opens
            const textarea = this.querySelector('textarea');
            if (textarea) {
                textarea.value = '';
            }
        });
        
        modalElement.addEventListener('hidden.bs.modal', function() {
            // Reset form when modal closes
            const form = this.querySelector('form');
            if (form) {
                form.reset();
            }
            
            // Force remove backdrop if still exists
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            // Remove modal-open class from body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });

        // Handle form submission in modal
        const form = modalElement.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const textarea = form.querySelector('textarea[name="rejection_reason"]');
                if (textarea && textarea.value.trim() === '') {
                    e.preventDefault();
                    alert('Alasan penolakan harus diisi!');
                    textarea.focus();
                    return false;
                }
            });
        }
    });

    // Handle reject button clicks
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            // Ensure any existing modals are properly closed
            const existingModals = document.querySelectorAll('.modal.show');
            existingModals.forEach(function(modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
            });
            
            // Clean up any leftover backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(function(backdrop) {
                backdrop.remove();
            });
        });
    });
});

// Additional cleanup function
function cleanupModals() {
    // Remove all modal backdrops
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(function(backdrop) {
        backdrop.remove();
    });
    
    // Reset body classes and styles
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

// Call cleanup on page unload
window.addEventListener('beforeunload', cleanupModals);
</script>
@endpush