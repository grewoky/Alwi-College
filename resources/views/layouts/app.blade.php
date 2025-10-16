<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Alwi College') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 text-gray-900">
<div class="min-h-full">

  {{-- Topbar --}}
  <header class="bg-white border-b">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="/" class="inline-flex items-center gap-2">
          <div class="h-8 w-8 rounded-xl bg-blue-600"></div>
          <span class="font-semibold">Alwi College</span>
        </a>
        {{-- breadcrumbs slot (opsional) --}}
        @isset($breadcrumbs)
          <div class="text-sm text-gray-500 ml-4">{{ $breadcrumbs }}</div>
        @endisset
      </div>

      <div class="flex items-center gap-3">
        {{-- user dropdown minimal --}}
        @auth
          <div class="text-sm">
            <span class="text-gray-500">Hi,</span>
            <span class="font-medium">{{ auth()->user()->name }}</span>
          </div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm px-3 py-1.5 rounded-lg border hover:bg-gray-100">Logout</button>
          </form>
        @else
          <a class="text-sm px-3 py-1.5 rounded-lg border hover:bg-gray-100" href="{{ route('login') }}">Login</a>
        @endauth
      </div>
    </div>
  </header>

  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-12 gap-6">
    {{-- Sidebar --}}
    <aside class="col-span-12 md:col-span-3 lg:col-span-2">
      <x-role-sidebar />
    </aside>

    {{-- Main content --}}
    <main class="col-span-12 md:col-span-9 lg:col-span-10">
      @if (session('ok'))
        <x-toast type="success" :message="session('ok')" />
      @endif

      {{ $slot }}
    </main>
  </div>
</div>
</body>
</html>
