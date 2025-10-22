<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfoFile;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;  
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;    // ⬅️ penting
 use Illuminate\Support\Facades\Log;
class InfoFileController extends Controller
{
    private function assertStudentUser(): void
{
    $u = Auth::user();
    $isStudent = DB::table('model_has_roles')
        ->join('roles','roles.id','=','model_has_roles.role_id')
        ->where('model_has_roles.model_type', get_class($u))
        ->where('model_has_roles.model_id', $u->id)
        ->where('roles.name','student')
        ->exists();

    abort_unless($isStudent, 403, 'Unauthorized.');
}

    public function destroy(InfoFile $info)
    {
        try {
            // Hapus file fisik (jika ada)
            if ($info->file_path && Storage::disk('public')->exists($info->file_path)) {
                Storage::disk('public')->delete($info->file_path);
            }
            
            // Hapus record di DB
            $info->delete();
            
            return back()->with('ok', 'File berhasil dihapus.');
        } catch (\Exception $e) {
         
            return back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    // Halaman pelajar: form upload + daftar file miliknya
    public function index()
    {
            $this->assertStudentUser(); // ⬅️ tambahkan

        $student = Student::firstOrCreate(['user_id' => Auth::id()]); // ⬅️ pakai Auth::id()
        $files = InfoFile::where('student_id', $student->id)->latest()->get();
        return view('info.index', compact('files'));
    }

    // Aksi upload oleh pelajar
    public function store(Request $r)
    {
        // Supported MIME types dengan ukuran maksimal berbeda per tipe
        $r->validate([
            'school'    => 'nullable|max:120',
            'class_name' => 'nullable|max:50',
            'subject'   => 'nullable|max:120',
            'title'     => 'required|max:120',
            'material'  => 'nullable|max:255',
            'file'      => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,txt,zip,rar,7z|max:10240',
        ]);

        try {
            $student = Student::firstOrCreate(['user_id' => Auth::id()]);
            $path = $r->file('file')->store('info_files', 'public');
            
            $extension = $this->getFileExtension($path);
            $fileType = $this->getFileType($extension);

            $infoFile = InfoFile::create([
                'student_id' => $student->id,
                'school'     => $r->school,
                'class_name' => $r->class_name,
                'subject'    => $r->subject,
                'title'      => $r->title,
                'material'   => $r->material,
                'file_path'  => $path,
            ]);
            
            Log::info('File uploaded', [
                'student_id' => $student->id,
                'file_type' => $fileType,
                'file_extension' => $extension,
                'file_path' => $path
            ]);

            return back()->with('ok', 'File ' . $fileType . ' berhasil diunggah!');
        } catch (\Exception $e) {
            Log::error('File upload error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
        }
    }

    // Halaman guru/admin: lihat semua file pelajar
    public function listAll()
    {
        $files = InfoFile::with('student.user')->latest()->get();
        return view('info.list', compact('files'));
    }

    // Halaman download options
    public function showDownloadOptions()
    {
        // Authorization check
        $user = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();
        
        abort_unless($isAdmin, 403, 'Unauthorized');
        
        return view('info.download-options');
    }

    // Guru/admin download file tertentu
    public function download(InfoFile $info)
    {
        // Authorization: only admin/teacher can download
        $user = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();
        
        $isTeacher = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'teacher')
            ->exists();
        
        abort_unless($isAdmin || $isTeacher, 403, 'Unauthorized');
        
        // Check if file exists
        if (!Storage::disk('public')->exists($info->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }
        
        // Download using response helper with proper path
        $filePath = storage_path('app/public/' . $info->file_path);
        $fileName = pathinfo($info->file_path, PATHINFO_FILENAME);
        
        return response()->download($filePath, $fileName);
    }

    /**
     * Supported MIME types untuk berbagai file format
     */
    private function getSupportedFileTypes(): array
    {
        return [
            'pdf'   => ['application/pdf', 'icon' => '📄'],
            'doc'   => ['application/msword', 'icon' => '📝'],
            'docx'  => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'icon' => '📝'],
            'xls'   => ['application/vnd.ms-excel', 'icon' => '📊'],
            'xlsx'  => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'icon' => '📊'],
            'ppt'   => ['application/vnd.ms-powerpoint', 'icon' => '🎨'],
            'pptx'  => ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'icon' => '🎨'],
            'jpg'   => ['image/jpeg', 'icon' => '🖼️'],
            'jpeg'  => ['image/jpeg', 'icon' => '🖼️'],
            'png'   => ['image/png', 'icon' => '🖼️'],
            'gif'   => ['image/gif', 'icon' => '🖼️'],
            'txt'   => ['text/plain', 'icon' => '📄'],
            'zip'   => ['application/zip', 'icon' => '📦'],
            'rar'   => ['application/x-rar-compressed', 'icon' => '📦'],
            '7z'    => ['application/x-7z-compressed', 'icon' => '📦'],
        ];
    }

    /**
     * Get file extension dari path
     */
    private function getFileExtension(string $filePath): string
    {
        return strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    }

    /**
     * Get file type (e.g., 'PDF', 'Image', 'Document', 'Spreadsheet')
     */
    private function getFileType(string $extension): string
    {
        $imageExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        $docExt = ['pdf', 'doc', 'docx', 'txt'];
        $spreadsheetExt = ['xls', 'xlsx'];
        $presentationExt = ['ppt', 'pptx'];
        $archiveExt = ['zip', 'rar', '7z'];

        if (in_array($extension, $imageExt)) return 'Gambar';
        if (in_array($extension, $docExt)) return 'Dokumen';
        if (in_array($extension, $spreadsheetExt)) return 'Spreadsheet';
        if (in_array($extension, $presentationExt)) return 'Presentasi';
        if (in_array($extension, $archiveExt)) return 'Arsip';
        
        return 'File Lainnya';
    }

    /**
     * Download file tertentu dengan informasi lengkap
     */
    public function downloadWithDetails(InfoFile $info)
    {
        // Authorization: only admin/teacher can download
        $user = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();
        
        $isTeacher = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'teacher')
            ->exists();
        
        abort_unless($isAdmin || $isTeacher, 403, 'Unauthorized');
        
        // Check if file exists
        if (!Storage::disk('public')->exists($info->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }
        
        try {
            $filePath = storage_path('app/public/' . $info->file_path);
            $fileName = pathinfo($info->file_path, PATHINFO_FILENAME);
            $extension = $this->getFileExtension($info->file_path);
            
            // Log download activity
            Log::info('File downloaded', [
                'file_id' => $info->id,
                'user_id' => Auth::id(),
                'file_name' => $fileName,
                'file_type' => $extension,
                'file_path' => $info->file_path
            ]);
            
            return response()->download($filePath, $fileName);
        } catch (\Exception $e) {
            Log::error('Download file error', [
                'file_id' => $info->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * Download multiple files by file type (PDF, Images, Documents, etc)
     */
    public function downloadByType(Request $request)
    {
        // Authorization check
        $user = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();
        
        abort_unless($isAdmin, 403, 'Unauthorized');
        
        $fileType = $request->input('type', '');
        
        if (!$fileType) {
            return back()->with('error', 'Tipe file tidak ditentukan');
        }
        
        // Get all files
        $allFiles = InfoFile::all();
        $filteredFiles = $allFiles->filter(function ($file) use ($fileType) {
            $extension = $this->getFileExtension($file->file_path);
            $fileTypeClass = $this->getFileType($extension);
            return strtolower($fileTypeClass) === strtolower($fileType);
        });
        
        if ($filteredFiles->isEmpty()) {
            return back()->with('error', 'Tidak ada file tipe ' . $fileType);
        }
        
        try {
            $zip = new \ZipArchive();
            $zipFileName = 'files-' . strtolower($fileType) . '-' . now()->format('Ymd-His') . '.zip';
            $zipFilePath = storage_path('app/temp/' . $zipFileName);
            
            // Create temp directory if not exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
                foreach ($filteredFiles as $file) {
                    $filePath = storage_path('app/public/' . $file->file_path);
                    
                    if (file_exists($filePath)) {
                        $studentName = $file->student->user->name ?? 'Unknown';
                        $localName = $studentName . '/' . basename($filePath);
                        $zip->addFile($filePath, $localName);
                    }
                }
                $zip->close();
                
                Log::info('Batch download by type', [
                    'user_id' => Auth::id(),
                    'file_type' => $fileType,
                    'files_count' => $filteredFiles->count()
                ]);
                
                return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
            }
            
            return back()->with('error', 'Gagal membuat file ZIP');
        } catch (\Exception $e) {
            Log::error('Download by type error', [
                'type' => $fileType,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * Download multiple selected files
     */
    public function downloadSelected(Request $request)
    {
        // Authorization check
        $user = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();
        
        abort_unless($isAdmin, 403, 'Unauthorized');
        
        $fileIds = $request->input('file_ids', []);
        
        if (empty($fileIds)) {
            return back()->with('error', 'Pilih minimal satu file');
        }
        
        $files = InfoFile::whereIn('id', $fileIds)->get();
        
        if ($files->isEmpty()) {
            return back()->with('error', 'File tidak ditemukan');
        }
        
        try {
            $zip = new \ZipArchive();
            $zipFileName = 'selected-files-' . now()->format('Ymd-His') . '.zip';
            $zipFilePath = storage_path('app/temp/' . $zipFileName);
            
            // Create temp directory if not exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
                foreach ($files as $file) {
                    $filePath = storage_path('app/public/' . $file->file_path);
                    
                    if (file_exists($filePath)) {
                        $studentName = $file->student->user->name ?? 'Unknown';
                        $localName = $studentName . '/' . basename($filePath);
                        $zip->addFile($filePath, $localName);
                    }
                }
                $zip->close();
                
                Log::info('Download selected files', [
                    'user_id' => Auth::id(),
                    'files_count' => $files->count()
                ]);
                
                return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
            }
            
            return back()->with('error', 'Gagal membuat file ZIP');
        } catch (\Exception $e) {
            Log::error('Download selected error', [
                'file_ids' => $fileIds,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * Download semua files sebagai ZIP
     */
    public function downloadAll()
    {
        // Authorization check
        $user = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();
        
        abort_unless($isAdmin, 403, 'Unauthorized');
        
        $files = InfoFile::all();
        
        if ($files->isEmpty()) {
            return back()->with('error', 'Tidak ada file untuk didownload');
        }

        try {
            $zip = new \ZipArchive();
            $zipFileName = 'info-files-' . now()->format('Ymd-His') . '.zip';
            $zipFilePath = storage_path('app/temp/' . $zipFileName);

            // Create temp directory if not exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
                foreach ($files as $file) {
                    $filePath = storage_path('app/public/' . $file->file_path);
                    
                    if (file_exists($filePath)) {
                        $studentName = $file->student->user->name ?? 'Unknown';
                        $localName = $studentName . '/' . basename($filePath);
                        $zip->addFile($filePath, $localName);
                    }
                }
                $zip->close();
                
                Log::info('Download all files', [
                    'user_id' => Auth::id(),
                    'files_count' => $files->count()
                ]);

                return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
            }

            return back()->with('error', 'Gagal membuat file ZIP');
        } catch (\Exception $e) {
            Log::error('Download all error', [
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Gagal membuat file ZIP: ' . $e->getMessage());
        }
    }

    /**
     * Get file statistics by type
     */
    public function getFileStats()
    {
        $user = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();
        
        abort_unless($isAdmin, 403, 'Unauthorized');
        
        $files = InfoFile::all();
        $stats = [
            'total' => $files->count(),
            'byType' => [],
            'bySize' => 0,
        ];
        
        foreach ($files as $file) {
            $extension = $this->getFileExtension($file->file_path);
            $fileType = $this->getFileType($extension);
            
            if (!isset($stats['byType'][$fileType])) {
                $stats['byType'][$fileType] = 0;
            }
            $stats['byType'][$fileType]++;
            
            $filePath = storage_path('app/public/' . $file->file_path);
            if (file_exists($filePath)) {
                $stats['bySize'] += filesize($filePath);
            }
        }
        
        $stats['bySize'] = round($stats['bySize'] / (1024 * 1024), 2); // Convert to MB
        
        return response()->json($stats);
    }
}
