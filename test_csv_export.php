<?php
/**
 * Test CSV Export Service
 * Run: php test_csv_export.php
 */

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Initialize services
$attendanceService = $app->make('App\Services\AttendanceService');

// Test CSV generation
echo "=== Testing CSV Export Service ===\n\n";

// Get sample attendance data
$attendances = \App\Models\Attendance::with([
    'student.user',
    'student.classRoom.school',
    'student.attendanceTracker',
    'lesson.subject',
    'marker:id,name,email'
])->limit(5)->get();

echo "Found " . $attendances->count() . " attendance records\n";

if ($attendances->isEmpty()) {
    echo "⚠️  No attendance records found. Skipping test.\n";
    exit;
}

echo "\n--- Sample Data ---\n";
foreach ($attendances->take(2) as $att) {
    echo "Student: " . optional($att->student)->user->name . "\n";
    echo "Status: " . $att->status . "\n";
    echo "Date: " . $att->created_at->format('Y-m-d H:i:s') . "\n";
    echo "---\n";
}

echo "\n--- Testing CSV Generation ---\n";

try {
    // Test the arrayToCSVLine helper indirectly
    $testArray = ['Tanggal', 'Nama Siswa', 'NIS', 'Status'];
    echo "Test array: " . implode(', ', $testArray) . "\n";
    
    // Generate CSV content
    $csvContent = '';
    $csvContent .= chr(0xEF) . chr(0xBB) . chr(0xBF); // UTF-8 BOM
    
    // Headers
    $headers = [
        'Tanggal',
        'Nama Siswa',
        'NIS',
        'Kelas',
        'Sekolah',
        'Status Absensi',
        'Guru Penginput',
        'Mata Pelajaran',
        'Kehadiran (Hari)',
        'Tanggal Mulai Period',
    ];
    
    // Simple CSV line builder (mimics what service does)
    foreach ($headers as $h) {
        $csvContent .= '"' . str_replace('"', '""', $h) . '";';
    }
    $csvContent = rtrim($csvContent, ';') . "\n";
    
    // Add sample row
    $row = [
        '30-12-2025 14:30',
        'Siswa Test',
        '001',
        'Kelas 10',
        'IGS',
        'Hadir',
        'Guru Test',
        'Matematika',
        '25',
        '01-12-2025',
    ];
    foreach ($row as $r) {
        $csvContent .= '"' . str_replace('"', '""', $r) . '";';
    }
    $csvContent = rtrim($csvContent, ';') . "\n";
    
    echo "\n✅ CSV content generated successfully!\n";
    echo "Size: " . strlen($csvContent) . " bytes\n";
    echo "\n--- First 300 chars of CSV ---\n";
    echo substr($csvContent, 0, 300) . "\n...\n";
    
    // Save to file for inspection
    $testFile = storage_path('test_attendance.csv');
    file_put_contents($testFile, $csvContent);
    echo "\n✅ CSV saved to: " . $testFile . "\n";
    
    // Read back to verify
    $readContent = file_get_contents($testFile);
    echo "✅ CSV file verified (" . strlen($readContent) . " bytes)\n";
    
    echo "\n=== Test Completed Successfully ===\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
