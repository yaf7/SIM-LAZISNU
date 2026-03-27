@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="container">
  <div class="row">
    <!-- Sidebar Menu -->
    <div class="col-md-3">
      <ul class="list-group">
        <li class="list-group-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="list-group-item"><a href="{{ route('donate.form') }}">Form Donasi</a></li>
        <li class="list-group-item"><a href="{{ route('submissions.index') }}">Pengajuan</a></li>
        @can('admin')
          <li class="list-group-item"><a href="{{ route('admin.donations') }}">Pengelolaan Dana</a></li>
          <li class="list-group-item"><a href="{{ route('admin.submissions') }}">Verifikasi Pengajuan</a></li>
        @endcan
      </ul>
    </div>

    <!-- Content -->
    <div class="col-md-9">
      @if(Route::is('dashboard'))
        <h3>Selamat Datang, {{ Auth::user()->name }}</h3>
        <p>Total Donasi Berhasil: <strong>Rp{{ number_format($totalDonasi,2,',','.') }}</strong></p>
      @endif
      @yield('dashboard-content')
    </div>
  </div>
</div>
@endsection