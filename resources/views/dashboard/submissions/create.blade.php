@extends('layouts.app')
@section('title','Ajukan Baru')
@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Form Pengajuan Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('submissions.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Pengajuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Masukkan judul pengajuan" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Total Pengajuan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount') }}" 
                                       placeholder="0" min="1" step="1" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Masukkan jumlah dana yang diajukan (dalam Rupiah)</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Jelaskan tujuan dan detail pengajuan Anda" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('submissions.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Format number input untuk amount
document.getElementById('amount').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    this.value = value;
});
</script>

@endsection