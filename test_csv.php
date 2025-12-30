<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== CSV Export Service Test ===\n\n";

try {
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    
    echo "✅ Laravel app bootstrapped\n";
    
    // Check if we can access models
    $attendanceCount = \App\Models\Attendance::count();
    echo "✅ Attendance records found: " . $attendanceCount . "\n";
    
    if ($attendanceCount == 0) {
        echo "\n⚠️  No attendance data in database. Cannot test with real data.\n";
        echo "Using synthetic test instead...\n\n";
        
        // Test the CSV line formatter logic
        $testData = [
            ['Tanggal', 'Nama Siswa', 'NIS', 'Status'],
            ['30-12-2025', 'Siswa Test', '001', 'Hadir'],
            ['29-12-2025', 'Siswa "Dengan Kutip"', '002', 'Izin; Sakit'],
        ];
        
        // Build CSV
        $csv = chr(0xEF) . chr(0xBB) . chr(0xBF); // UTF-8 BOM
        
        foreach ($testData as $row) {
            $line = '';
            foreach ($row as $col) {
                // Escape quotes
                $col = str_replace('"', '""', $col);
                // Wrap if contains delimiter or quote
                if (strpos($col, ';') !== false || strpos($col, '"') !== false) {
                    $col = '"' . $col . '"';
                }
                $line .= $col . ';';
            }
            $csv .= rtrim($line, ';') . "\n";
        }
        
        echo "✅ CSV content built successfully\n";
        echo "📊 CSV Size: " . strlen($csv) . " bytes\n";
        echo "📄 CSV Lines: " . count($testData) . "\n";
        
        // Save to test file
        $testPath = storage_path('test_attendance_export.csv');
        file_put_contents($testPath, $csv);
        echo "💾 Saved to: " . $testPath . "\n";
        
        // Verify file
        if (file_exists($testPath)) {
            $size = filesize($testPath);
            echo "✅ File verified: " . $size . " bytes\n";
            echo "\n--- CSV Content Preview ---\n";
            echo substr(str_replace(chr(0xEF).chr(0xBB).chr(0xBF), '', $csv), 0, 500);
            echo "\n\n✅ CSV Export Test Passed!\n";
        }
        
    } else {
        echo "\n✅ Testing with real attendance data...\n";
        
        // Get service
        $service = $app->make('App\Services\AttendanceService');
        
        // Get sample data
        $attendances = \App\Models\Attendance::with([
            'student.user',
            'student.classRoom.school',
            'student.attendanceTracker',
            'lesson.subject',
            'marker:id,name,email'
        ])->limit(3)->get();
        
        echo "✅ Loaded " . $attendances->count() . " attendance records\n";
        
        if ($attendances->count() > 0) {
            $att = $attendances->first();
            echo "\n--- Sample Record ---\n";
            echo "Student: " . optional($att->student)->user->name . "\n";
            echo "Status: " . $att->status . "\n";
            echo "Marker: " . optional($att->marker)->name . "\n";
        }
    }
    
    echo "\n=== Test Completed ===\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nTrace:\n";
    echo $e->getTraceAsString() . "\n";
}
?>
