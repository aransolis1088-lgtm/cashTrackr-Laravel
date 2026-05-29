<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @fonts

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body>
    <form class="mt-14 space-y-5" novalidate>
        <div class="space-y-2">
            <label class="font-bold text-2xl block" for="name">Nombre</label>

            <input id="name" type="text" placeholder="Tu Nombre" class="w-full border border-gray-300 p-3 rounded-lg"
                name="name" />
        </div>

        <div class="space-y-2">
            <label class="font-bold text-2xl block" for="email">Email</label>

            <input id="email" type="email" placeholder="Email de Registro"
                class="w-full border border-gray-300 p-3 rounded-lg" name="email" />
        </div>

        <div class="space-y-2">
            <label class="font-bold text-2xl block">Password</label>

            <input type="password" placeholder="Password de Registro"
                class="w-full border border-gray-300 p-3 rounded-lg" name="password" />
        </div>

        <div class="space-y-2">
            <label class="font-bold text-2xl block" for="password_confirmation">Repetir Password</label>

            <input type="password" placeholder="Password de Registro"
                class="w-full border border-gray-300 p-3 rounded-lg" name="password_confirmation" />
        </div>

        <input type="submit" value='Registrarme'
            class="bg-purple-950 hover:bg-purple-800 w-full p-3 rounded-lg text-white font-bold  text-xl cursor-pointer" />
    </form>
</body>