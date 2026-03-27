@extends('layouts.app')
@section('title','Laporan Saya')
@section('content')
<div class="container">
{{-- Statistik Ringkas --}}
<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card text-white bg-success h-100">
      <div class="card-body">
        <h6>Total Donasi</h6>
        <h4>
          Rp {{ number_format($stats['total_donasi'], 0, ',', '.') }}
        </h4>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card text-white bg-primary h-100">
      <div class="card-body">
        <h6>Total Transaksi</h6>
        <h4>{{ $stats['count_donasi'] }}</h4>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card text-white bg-warning h-100">
      <div class="card-body">
        <h6>Menunggu Bayar</h6>
        <h4>{{ $stats['pending_donasi'] }}</h4>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card text-white bg-info h-100">
      <div class="card-body">
        <h6>Approved</h6>
        <h4>{{ $stats['approved_donasi'] }}</h4>
      </div>
    </div>
  </div>
</div>

  {{-- Grafik Tren Donasi --}}
 <div class="card mb-4">
  <div class="card-header bg-light">
    <i class="bi bi-bar-chart-line"></i> Tren Donasi (6 Bulan Terakhir)
  </div>
  <div class="card-body">
    <canvas id="donationTrendChart" height="100"></canvas>
  </div>
</div>

  <div class="row">
    {{-- Tabel Riwayat Donasi --}}
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header bg-success text-white">
          <i class="bi bi-wallet2"></i> Riwayat Donasi
        </div>
        <div class="card-body p-0">
          @if($donations->isEmpty())
            <p class="text-center text-muted py-4">Belum ada donasi.</p>
          @else
            <div class="table-responsive">
              <table class="table mb-0">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Tgl</th>
                    <th>Rp</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($donations as $i => $d)
                  <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->created_at)->format('d M Y') }}</td>
                    <td>{{ number_format($d->amount,0,',','.') }}</td>
                    <td>
                      <span class="badge bg-{{ $d->status=='approved'?'success':($d->status=='rejected'?'danger':'warning') }}">
                        {{ ucfirst($d->status) }}
                      </span>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- List Riwayat Pengajuan --}}
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header bg-primary text-white">
          <i class="bi bi-card-checklist"></i> Riwayat Pengajuan
        </div>
        <div class="card-body p-0">
          @if($subs->isEmpty())
            <p class="text-center text-muted py-4">Belum ada pengajuan.</p>
          @else
            <ul class="list-group list-group-flush">
              @foreach($subs as $s)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $s->title }}</strong><br>
                  <small class="text-muted">{{ \Carbon\Carbon::parse($s->created_at)->format('d M Y') }}</small>
                </div>
                <span class="badge bg-{{ $s->status=='approved'?'success':($s->status=='rejected'?'danger':'warning') }}">
                  {{ ucfirst($s->status) }}
                </span>
              </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const monthly = @json($monthly);  
    const months  = monthly.map(item => item.month);   // ["2025-01", "2025-02", …]
    const totals  = monthly.map(item => item.total);   // [12345, 67890, …]

    // Format labels "2025-01" → "Jan 2025"
    const labels = months.map(m => {
      const [y, mo] = m.split('-');
      return new Intl.DateTimeFormat('id', {
        month: 'short', year: 'numeric'
      }).format(new Date(y, mo - 1));
    });

    const ctx = document.getElementById('donationTrendChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Donasi per Bulan',
          data: totals,
          fill: true,
          tension: 0.4,
          borderWidth: 2,
          pointRadius: 4,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { mode: 'index', intersect: false }
        },
        scales: {
          x: {
            title: { display: true, text: 'Bulan' }
          },
          y: {
            beginAtZero: true,
            title: { display: true, text: 'Rp' },
            ticks: {
              callback: val => 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
            }
          }
        }
      }
    });
  });
</script>
@endpush
