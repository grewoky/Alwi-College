<?php

/**
 * TESTING PAYMENT LOGIC
 * 
 * Jalankan di Tinker untuk test:
 * php artisan tinker
 * 
 * Paste kode di bawah
 */

// ====== TEST 1: Cek Format Month Period ======
$currentMonth = now()->format('m');
$currentYear = now()->format('Y');
$monthPeriod = $currentMonth . '-' . $currentYear;

echo "Current Month: " . $monthPeriod; // Contoh: 10-2025

// ====== TEST 2: Cek Payment untuk Siswa Tertentu ======
$student = \App\Models\Student::find(1); // Ganti ID sesuai kebutuhan
$payment = $student->payment()
    ->where('month_period', $monthPeriod)
    ->where('status', 'approved')
    ->first();

if($payment) {
    echo "Siswa SUDAH BAYAR bulan ini";
} else {
    echo "Siswa BELUM BAYAR bulan ini";
}

// ====== TEST 3: Cek Semua Payment Siswa ======
$allPayments = $student->payment()->get();
dd($allPayments);

// ====== TEST 4: Create Test Payment ======
// Untuk membuat pembayaran test, gunakan:
$student = \App\Models\Student::find(1);
$student->payment()->create([
    'month_period' => '10-2025', // Bulan sekarang
    'amount' => 500000,
    'status' => 'approved',
    'proof_path' => '/proof/test.pdf',
    'note' => 'Test payment'
]);

echo "Payment berhasil dibuat!";

// ====== TEST 5: Check Total Students & Teachers ======
$totalStudents = \App\Models\Student::count();
$totalTeachers = \App\Models\Teacher::count();

echo "Total Students: " . $totalStudents;
echo "Total Teachers: " . $totalTeachers;

// ====== TEST 6: Full Dashboard Data ======
// Jalankan seluruh query yang ada di controller
$student = \App\Models\Student::firstOrCreate(['user_id' => 1]);
$presentCount = \App\Models\Attendance::where('student_id', $student->id)
    ->where('status', 'present')
    ->count();
$lastPayment = \App\Models\Payment::where('student_id', $student->id)
    ->latest()
    ->first();
$payments = \App\Models\Payment::where('student_id', $student->id)->get();
$totalStudents = \App\Models\Student::count();
$totalTeachers = \App\Models\Teacher::count();

echo "Present Count: " . $presentCount;
echo "Last Payment: " . $lastPayment;
echo "Total Payments: " . $payments->count();
echo "Total Students: " . $totalStudents;
echo "Total Teachers: " . $totalTeachers;

?>
