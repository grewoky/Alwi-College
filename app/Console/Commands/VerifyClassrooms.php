<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassRoom;

class VerifyClassrooms extends Command
{
    protected $signature = 'verify:classrooms';
    protected $description = 'Verifikasi 3 kelas sudah dibuat dengan benar';

    public function handle()
    {
        $this->info('📊 Verifikasi Data Kelas');
        $this->line('─────────────────────────');

        $classrooms = ClassRoom::orderBy('grade')->get();

        if ($classrooms->isEmpty()) {
            $this->error('❌ Tidak ada kelas ditemukan!');
            return;
        }

        $this->info('✅ Total Kelas: ' . $classrooms->count());
        $this->line('');

        // Tampilkan tabel
        $this->table(
            ['ID', 'Kelas', 'Grade', 'Kapasitas'],
            $classrooms->map(fn($c) => [
                $c->id,
                $c->name,
                $c->grade,
                $c->capacity,
            ])->toArray()
        );

        $this->line('');
        
        if ($classrooms->count() == 3) {
            $this->info('✅ Verifikasi BERHASIL! Data sudah sesuai.');
        } else {
            $this->warn('⚠️  Jumlah kelas tidak sesuai (harus 3)');
        }
    }
}
