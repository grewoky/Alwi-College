<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== CSV Export Response Test ===\n\n";

try {
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    
    $service = $app->make('App\Services\AttendanceService');
    
    // Get attendance data
    $attendances = \App\Models\Attendance::with([
        'student.user',
        'student.classRoom.school',
        'student.attendanceTracker',
        'lesson.subject',
        'marker:id,name,email'
    ])->limit(3)->get();
    
    echo "✅ Loaded " . $attendances->count() . " attendance records\n\n";
    
    if ($attendances->count() > 0) {
        // Generate response
        $response = $service->downloadAttendanceCSV($attendances);
        
        echo "--- Response Headers ---\n";
        echo "Status: " . $response->status() . "\n";
        echo "Content-Type: " . $response->headers->get('content-type') . "\n";
        echo "Content-Length: " . $response->headers->get('content-length') . "\n";
        echo "Content-Disposition: " . $response->headers->get('content-disposition') . "\n";
        echo "Cache-Control: " . $response->headers->get('cache-control') . "\n";
        echo "Pragma: " . $response->headers->get('pragma') . "\n";
        echo "Expires: " . $response->headers->get('expires') . "\n";
        
        $content = $response->getContent();
        
        echo "\n--- Response Content ---\n";
        echo "CSV Content Size: " . strlen($content) . " bytes\n";
        echo "First 200 chars:\n";
        echo substr(str_replace(chr(0xEF).chr(0xBB).chr(0xBF), '[BOM]', $content), 0, 200) . "\n";
        
        echo "\n✅ CSV Response Generated Successfully!\n";
        
    } else {
        echo "⚠️  No attendance records found\n";
        echo "Cannot generate CSV without data\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
?>
