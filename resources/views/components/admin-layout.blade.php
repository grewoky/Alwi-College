@props(['title' => 'Admin Dashboard'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} • Alwi College</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 text-gray-900">
<div class="min-h-full">

  {{-- Admin Navbar Component --}}
  <x-admin-navbar />

  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
    @if (session('ok'))
      <x-toast type="success" :message="session('ok')" />
    @endif

    {{ $slot }}
  </div>
</div>
</body>
</html>
