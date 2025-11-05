<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassRoom;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class CleanupClassrooms extends Command
{
    protected $signature = 'cleanup:classrooms';
    protected $description = 'Hapus semua kelas lama dan buat 3 kelas baru (10, 11, 12)';

    public function handle()
    {
        $this->info('🗑️ Mempersiapkan pembersihan data...');
        
        // ✅ STEP 1: Disable foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->line('✅ Foreign key checks disabled');

        // ✅ STEP 2: Hapus semua lessons terlebih dahulu
        $this->info('🗑️ Menghapus semua lessons...');
        Lesson::truncate();
        $this->line('✅ Semua lessons dihapus');

        // ✅ STEP 3: Hapus semua class_rooms
        $this->info('🗑️ Menghapus semua kelas lama...');
        ClassRoom::truncate();
        $this->line('✅ Semua kelas lama dihapus');

        // ✅ STEP 4: Enable foreign key check kembali
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->line('✅ Foreign key checks enabled');

        // ✅ STEP 5: Buat 3 kelas baru
        $this->info('📝 Membuat 3 kelas baru...');
        
        ClassRoom::create([
            'school_id' => 1,
            'grade' => 10,
            'name' => 'Kelas 10',
            'capacity' => 40,
        ]);

        ClassRoom::create([
            'school_id' => 1,
            'grade' => 11,
            'name' => 'Kelas 11',
            'capacity' => 40,
        ]);

        ClassRoom::create([
            'school_id' => 1,
            'grade' => 12,
            'name' => 'Kelas 12',
            'capacity' => 40,
        ]);

        $this->info('✅ 3 kelas baru berhasil dibuat!');
        $this->info('📊 Total kelas sekarang: ' . ClassRoom::count());
        $this->info('✅ Cleanup selesai!');
    }
}