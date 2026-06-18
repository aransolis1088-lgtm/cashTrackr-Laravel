@extends('layouts.auth')

@section('title')
    Administra tus presupuestos
@endsection

@section('auth-content')
    @if (session('success'))
        <p class="bg-green-500 text-white my-10 rounded-lg text-sm p-2 text-center py-2">{{ session('success') }}</p>
    @endif
@endsection
