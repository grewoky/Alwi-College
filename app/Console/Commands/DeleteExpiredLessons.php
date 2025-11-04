<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Lesson;
use Illuminate\Support\Facades\Schema;

class DeleteExpiredLessons extends Command
{
    /**
     * Nama dan deskripsi command
     *
     * @var string
     */
    protected $signature = 'schedule:cleanup';

    protected $description = 'Hapus jadwal (lesson) yang sudah lewat tanggalnya. Berjalan otomatis setiap hari.';

    /**
     * Eksekusi command
     */
    public function handle()
    {
        $this->info('🔄 Memulai cleanup jadwal yang sudah lewat...');

        try {
            // Ambil hari kemarin (atau hari-hari sebelumnya)
            $today = Carbon::now()->startOfDay();

            // Cari semua jadwal yang date-nya < hari ini
            $expiredLessons = Lesson::where('date', '<', $today->toDateString())
                ->get();

            $deletedCount = 0;
            $details = [];

            if ($expiredLessons->count() === 0) {
                $this->info('✓ Tidak ada jadwal yang perlu dihapus.');
                Log::info('DeleteExpiredLessons: Tidak ada jadwal expired', [
                    'executed_at' => now(),
                ]);
                return;
            }

            // Loop dan hapus setiap jadwal yang sudah lewat
            foreach ($expiredLessons as $lesson) {
                // Simpan detail sebelum dihapus (untuk logging)
                $details[] = [
                    'lesson_id' => $lesson->id,
                    'date' => $lesson->date,
                    'classroom_id' => $lesson->class_room_id,
                    'teacher_id' => $lesson->teacher_id,
                    'subject_id' => $lesson->subject_id,
                    'start_time' => $lesson->start_time,
                    'end_time' => $lesson->end_time,
                ];

                // Simpan ke deleted_lessons_log table (jika tabel ada)
                if (Schema::hasTable('deleted_lessons_log')) {
                    DB::table('deleted_lessons_log')->insert([
                        'lesson_date' => $lesson->date,
                        'classroom_id' => $lesson->class_room_id,
                        'teacher_id' => $lesson->teacher_id,
                        'subject_id' => $lesson->subject_id,
                        'start_time' => $lesson->start_time,
                        'end_time' => $lesson->end_time,
                        'deleted_by' => 'system',
                        'deletion_reason' => 'Automated cleanup - lesson date has passed',
                        'deleted_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $lesson->delete();
                $deletedCount++;
            }

            $msg = "✅ Cleanup selesai! {$deletedCount} jadwal yang sudah lewat berhasil dihapus.";
            $this->info($msg);

            // Log ke database/file
            Log::info('DeleteExpiredLessons: Success', [
                'deleted_count' => $deletedCount,
                'executed_at' => now(),
                'deleted_dates' => $details,
            ]);

        } catch (\Exception $e) {
            $this->error("❌ Error saat cleanup: {$e->getMessage()}");
            
            Log::error('DeleteExpiredLessons: Failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'executed_at' => now(),
            ]);
        }
    }
}
