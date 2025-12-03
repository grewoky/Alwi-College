<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $title ?? config('app.name', 'Alwi College') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 text-gray-900 overflow-x-hidden">
<div class="min-h-full">

  {{-- Navbar Component (Menggantikan topbar dan sidebar) --}}
  <x-app-navbar />

  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
    @if (session('ok'))
      <x-toast type="success" :message="session('ok')" />
    @endif

    {{ $slot }}
  </div>
</div>
</body>
</html>
