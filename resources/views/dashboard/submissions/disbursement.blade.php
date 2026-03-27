@extends('layouts.app')
@section('title','Atur Pencairan')
@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Atur Metode Pencairan</h4>
                    <p class="mb-0 text-muted">Pengajuan: {{ $submission->title }}</p>
                    <p class="mb-0"><strong>Total: Rp {{ number_format($submission->amount, 0, ',', '.') }}</strong></p>
                </div>
                <div class="card-body">
                    <form action="{{ route('submissions.disbursement.store', $submission->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Metode Pencairan <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method" 
                                               id="bank_transfer" value="bank_transfer" 
                                               {{ old('method') == 'bank_transfer' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="bank_transfer">
                                            <i class="fas fa-university"></i> Transfer Bank
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method" 
                                               id="e_wallet" value="e_wallet" 
                                               {{ old('method') == 'e_wallet' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="e_wallet">
                                            <i class="fas fa-mobile-alt"></i> E-Wallet
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method" 
                                               id="cash" value="cash" 
                                               {{ old('method') == 'cash' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="cash">
                                            <i class="fas fa-money-bill"></i> Tunai
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('method')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="account_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                   id="account_name" name="account_name" value="{{ old('account_name') }}" 
                                   placeholder="Nama lengkap sesuai rekening/akun" required>
                            @error('account_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="account_number" class="form-label">Nomor Rekening/HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                   id="account_number" name="account_number" value="{{ old('account_number') }}" 
                                   placeholder="Nomor rekening atau nomor HP" required>
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bank Transfer Fields -->
                        <div id="bank_fields" class="method-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="bank_name" class="form-label">Nama Bank <span class="text-danger">*</span></label>
                                <select class="form-control @error('bank_name') is-invalid @enderror" 
                                        id="bank_name" name="bank_name">
                                    <option value="">Pilih Bank</option>
                                    <option value="BCA" {{ old('bank_name') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                    <option value="BNI" {{ old('bank_name') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                    <option value="BRI" {{ old('bank_name') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                    <option value="Mandiri" {{ old('bank_name') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                    <option value="CIMB Niaga" {{ old('bank_name') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                                    <option value="Danamon" {{ old('bank_name') == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                                    <option value="Permata" {{ old('bank_name') == 'Permata' ? 'selected' : '' }}>Permata</option>
                                    <option value="BTN" {{ old('bank_name') == 'BTN' ? 'selected' : '' }}>BTN</option>
                                    <option value="BSI" {{ old('bank_name') == 'BSI' ? 'selected' : '' }}>BSI</option>
                                    <option value="Lainnya" {{ old('bank_name') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- E-Wallet Fields -->
                        <div id="ewallet_fields" class="method-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="ewallet_type" class="form-label">Jenis E-Wallet <span class="text-danger">*</span></label>
                                <select class="form-control @error('ewallet_type') is-invalid @enderror" 
                                        id="ewallet_type" name="ewallet_type">
                                    <option value="">Pilih E-Wallet</option>
                                    <option value="GoPay" {{ old('ewallet_type') == 'GoPay' ? 'selected' : '' }}>GoPay</option>
                                    <option value="OVO" {{ old('ewallet_type') == 'OVO' ? 'selected' : '' }}>OVO</option>
                                    <option value="DANA" {{ old('ewallet_type') == 'DANA' ? 'selected' : '' }}>DANA</option>
                                    <option value="LinkAja" {{ old('ewallet_type') == 'LinkAja' ? 'selected' : '' }}>LinkAja</option>
                                    <option value="ShopeePay" {{ old('ewallet_type') == 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                                    <option value="Lainnya" {{ old('ewallet_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('ewallet_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Catatan tambahan jika ada">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Informasi:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pastikan data yang dimasukkan sudah benar</li>
                                <li>Proses pencairan akan ditinjau oleh admin</li>
                                <li>Anda akan mendapat notifikasi tentang status pencairan</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('submissions.show', $submission->id) }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan Data Pencairan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const methodRadios = document.querySelectorAll('input[name="method"]');
    const bankFields = document.getElementById('bank_fields');
    const ewalletFields = document.getElementById('ewallet_fields');

    function toggleFields() {
        const selectedMethod = document.querySelector('input[name="method"]:checked');
        
        // Hide all fields first
        bankFields.style.display = 'none';
        ewalletFields.style.display = 'none';
        
        // Clear required attributes
        document.getElementById('bank_name').required = false;
        document.getElementById('ewallet_type').required = false;
        
        if (selectedMethod) {
            if (selectedMethod.value === 'bank_transfer') {
                bankFields.style.display = 'block';
                document.getElementById('bank_name').required = true;
            } else if (selectedMethod.value === 'e_wallet') {
                ewalletFields.style.display = 'block';
                document.getElementById('ewallet_type').required = true;
            }
        }
    }

    // Add event listeners to radio buttons
    methodRadios.forEach(radio => {
        radio.addEventListener('change', toggleFields);
    });

    // Initialize on page load
    toggleFields();
});
</script>

@endsection