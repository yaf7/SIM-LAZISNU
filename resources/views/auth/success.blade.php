@extends('layouts.app')
@section('title', 'Success')
@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow text-center">
  <h2 class="text-2xl mb-4 text-green-600">Successfully</h2>
  <p>Your password has been reset successfully.</p>
  <a href="{{ route('login.form') }}" class="mt-4 inline-block bg-blue-700 text-white p-2 rounded">Continue</a>
</div>
@endsection