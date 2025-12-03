<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <style>
      .heading-inline{display:inline-block;position:relative;padding-left:.75rem}
      .heading-inline::before{content:"";position:absolute;left:0;top:55%;transform:translateY(-50%);height:.6em;width:.4em;background:linear-gradient(135deg,#16a34a,#22c55e);border-radius:.2em}
      html,body{touch-action:pan-x pan-y;-ms-touch-action:pan-x pan-y}
    </style>
    <title>{{ $title ?? config('app.name', 'Alwi College') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 text-gray-900 overflow-x-hidden">
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
