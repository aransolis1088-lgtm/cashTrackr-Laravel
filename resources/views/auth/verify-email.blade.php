@extends('layouts.auth')

@section('title')
    Confirma tu cuenta
@endsection

@section('auth-content')
    <p class="mt-5 text-lg">Tu cuenta fue creada con éxito. Por favor, verifica tu correo electrónico para confirmar tu
        cuenta.</p>

    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        <input type="submit" value='Reenviar correo de verificación'
            class="bg-amber-500 hover:bg-amber-800 w-full p-3 rounded-lg text-white font-bold  text-xl cursor-pointer mt-5" />
    </form>
@endsection
