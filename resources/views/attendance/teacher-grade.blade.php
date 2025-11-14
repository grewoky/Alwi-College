<x-app-layout>
    <x-slot name="title">Absensi • Pengajar • Kelas {{ $grade }}</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('attendance.teacher') }}" class="text-blue-600 hover:underline mb-2 inline-block">← Kembali</a>
                <h1 class="text-3xl font-bold">Kelas {{ $grade }} — Pilih Kelas untuk Absensi</h1>
            </div>

            @if($classRooms->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500">Tidak ditemukan kelas pada grade ini yang Anda ajar.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($classRooms as $classroom)
                        <a href="{{ route('attendance.mark', $classroom->id) }}" class="block p-4 rounded-lg bg-white border border-slate-200 hover:shadow-md">
                            <div class="font-bold text-lg">{{ $classroom->name }}</div>
                            <div class="text-sm text-slate-500 mt-1">{{ $classroom->students()->count() }} siswa • {{ $classroom->school->name ?? '-' }}</div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>