<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfoFile;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;  
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;    // ⬅️ penting
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
            $cloudinaryId = $info->file_public_id ?: $info->file_path;
            if ($info->file_url && $cloudinaryId) {
                try {
                    Cloudinary::destroy($cloudinaryId, ['invalidate' => true]);
                } catch (\Throwable $th) {
                    Log::warning('Failed to delete info file from Cloudinary', [
                        'info_id' => $info->id,
                        'public_id' => $cloudinaryId,
                        'error' => $th->getMessage(),
                    ]);
                }
            } elseif ($info->file_path && Storage::disk('public')->exists($info->file_path)) {
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
            'file'      => ['required','file','mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,txt,zip,rar,7z','max:10240'],
        ]);

        $this->enforceImageSizeLimit($r, ['file']);

        try {
            $student = Student::firstOrCreate(['user_id' => Auth::id()]);

            $fileInstance = $r->file('file');
            $originalName = $fileInstance->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $sanitizedName = preg_replace("/[^a-zA-Z0-9.-]/", "_", $fileName) ?: 'file';
            $finalName = $sanitizedName . '_' . time();

            $folder = 'info_files/' . $student->id;
            $publicId = Str::slug($finalName . '-' . Str::random(8));

            $options = [
                'folder' => $folder,
                'public_id' => $publicId,
                'resource_type' => 'auto',
                'overwrite' => true,
            ];

            if ($extension) {
                $options['format'] = $extension;
            }

            $upload = Cloudinary::uploadFile($fileInstance->getRealPath(), $options);

            $secureUrl = $upload->getSecurePath();
            $cloudinaryId = $upload->getPublicId();
            $fileType = $this->getFileType($extension);

            $infoFile = InfoFile::create([
                'student_id' => $student->id,
                'school'     => $r->school,
                'class_name' => $r->class_name,
                'subject'    => $r->subject,
                'title'      => $r->title,
                'material'   => $r->material,
                'file_path'  => $finalName . ($extension ? '.' . $extension : ''),
                'file_url'   => $secureUrl,
                'file_public_id' => $cloudinaryId,
            ]);
            
            Log::info('File uploaded', [
                'student_id' => $student->id,
                'file_type' => $fileType,
                'file_extension' => $extension,
                'file_path' => $infoFile->file_path,
                'cloudinary_public_id' => $cloudinaryId,
                'cloudinary_url' => $secureUrl,
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
        $r->validate([
        abort_unless($user !== null, 403, 'Unauthorized.');
        $isAdmin = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            'file'      => ['nullable','file','mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,txt,zip,rar,7z','max:10240'],
            'cloudinary_public_id' => ['nullable','string','max:255'],
            'cloudinary_secure_url' => ['nullable','url','max:2048'],
            'cloudinary_format' => ['nullable','string','max:20'],
            'cloudinary_original_filename' => ['nullable','string','max:255'],
            'cloudinary_resource_type' => ['nullable','string','max:50'],
            ->exists();
        
        $hasDirectUpload = $r->filled('cloudinary_public_id') && $r->filled('cloudinary_secure_url');

        if (! $hasDirectUpload && ! $r->hasFile('file')) {
            throw ValidationException::withMessages([
                'cloudinary_public_id' => 'Silakan unggah file melalui Cloudinary.',
            ]);
        }

        $this->enforceImageSizeLimit($r, ['file']);

        abort_unless($isAdmin, 403, 'Unauthorized');
        
            $cloudinaryId = null;
            $secureUrl = null;
            $extension = null;
            $fileType = 'File Lainnya';
            $storedFileName = null;

            if ($hasDirectUpload) {
                $cloudinaryId = $r->cloudinary_public_id;
                $secureUrl = $r->cloudinary_secure_url;
                $extension = strtolower((string) $r->input('cloudinary_format')) ?: null;
                $originalName = $r->input('cloudinary_original_filename') ?: basename($cloudinaryId);
                $sanitizedName = preg_replace("/[^a-zA-Z0-9.-]/", "_", $originalName) ?: 'file';
                $storedFileName = $extension ? $sanitizedName . '.' . $extension : $sanitizedName;
                $fileType = $this->getFileType((string) $extension);
            } else {
                $fileInstance = $r->file('file');
                $originalName = $fileInstance->getClientOriginalName();
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $sanitizedName = preg_replace("/[^a-zA-Z0-9.-]/", "_", $fileName) ?: 'file';
                $finalName = $sanitizedName . '_' . time();

                $folder = 'info_files/' . $student->id;
                $publicId = Str::slug($finalName . '-' . Str::random(8));

                $options = [
                    'folder' => $folder,
                    'public_id' => $publicId,
                    'resource_type' => 'auto',
                    'overwrite' => true,
                ];

                if ($extension) {
                    $options['format'] = $extension;
                }

                $upload = Cloudinary::uploadFile($fileInstance->getRealPath(), $options);

                $secureUrl = $upload->getSecurePath();
                $cloudinaryId = $upload->getPublicId();
                $fileType = $this->getFileType($extension);
                $storedFileName = $finalName . ($extension ? '.' . $extension : '');
            }

            if (! $storedFileName) {
                $basename = basename($cloudinaryId);
                $storedFileName = $extension ? $basename . '.' . $extension : $basename;
            }
                [$tempPath, $downloadName] = $this->prepareCloudinaryDownload($info);

                Log::info('File downloaded', [
                    'file_id' => $info->id,
                    'user_id' => Auth::id(),
                    'file_name' => $downloadName,
                    'file_public_id' => $info->file_public_id,
                ]);
                'file_path'  => $storedFileName,
                return response()->download($tempPath, $downloadName)->deleteFileAfterSend(true);
            } catch (\Throwable $th) {
                Log::error('Cloudinary download failed', [
                    'file_id' => $info->id,
                    'error' => $th->getMessage(),
                ]);
                return back()->with('error', 'Gagal mengunduh file dari Cloudinary.');
            }
        }

        if (!Storage::disk('public')->exists($info->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . $info->file_path);
        $originalFileName = pathinfo($info->file_path, PATHINFO_BASENAME);

        Log::info('File downloaded (legacy storage)', [
            'file_id' => $info->id,
            'user_id' => Auth::id(),
            'file_name' => $originalFileName,
            'file_path' => $info->file_path
        ]);

        return response()->download($filePath, $originalFileName);
    }

    /**
     * Serve student uploaded file to owner, teacher, or admin.
     */
    public function showFile(InfoFile $info)
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized.');

        // Load relation
        $info->loadMissing('student.user');

        // Check roles via DB table (works even without Spatie helper)
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

        // Allow if admin, teacher, or owner student
        if (! $isAdmin && ! $isTeacher && $info->student->user_id !== $user->id) {
            abort(403, 'Tidak diizinkan mengakses file ini.');
        }

        if ($info->file_url) {
            return redirect()->away($info->file_url);
        }

        if (! $info->file_path || ! Storage::disk('public')->exists($info->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $info->file_path);
        if (! file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($fullPath);
    }

    private function prepareCloudinaryDownload(InfoFile $info, ?string $preferredName = null): array
    {
        if (! $info->file_url) {
            throw new \RuntimeException('Cloudinary URL tidak tersedia.');
        }

        $fileName = $preferredName ?: $info->file_path;
        if (! $fileName) {
            $urlPath = parse_url($info->file_url, PHP_URL_PATH) ?: '';
            $fileName = $urlPath ? basename($urlPath) : 'file';
        }

        if (str_contains($fileName, '.') === false && $info->file_path) {
            $extension = pathinfo($info->file_path, PATHINFO_EXTENSION);
            if ($extension) {
                $fileName .= '.' . $extension;
            }
        }

        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir . '/' . Str::uuid()->toString() . '_' . $fileName;

        $response = Http::timeout(45)->sink($tempPath)->get($info->file_url);
        if (! $response->successful()) {
            @unlink($tempPath);
            throw new \RuntimeException('Gagal mengambil file dari Cloudinary.');
        }

        if (! file_exists($tempPath)) {
            throw new \RuntimeException('File sementara tidak ditemukan.');
        }

        return [$tempPath, $fileName];
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
        
        if ($info->file_url) {
            try {
                [$tempPath, $downloadName] = $this->prepareCloudinaryDownload($info);

                Log::info('File downloaded', [
                    'file_id' => $info->id,
                    'user_id' => Auth::id(),
                    'file_name' => $downloadName,
                    'file_public_id' => $info->file_public_id,
                ]);

                return response()->download($tempPath, $downloadName)->deleteFileAfterSend(true);
            } catch (\Exception $e) {
                Log::error('Download file error', [
                    'file_id' => $info->id,
                    'error' => $e->getMessage()
                ]);
                return back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
            }
        }

        if (!Storage::disk('public')->exists($info->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        try {
            $filePath = storage_path('app/public/' . $info->file_path);
            $originalFileName = pathinfo($info->file_path, PATHINFO_BASENAME);
            $extension = $this->getFileExtension($info->file_path);
            
            Log::info('File downloaded (legacy storage)', [
                'file_id' => $info->id,
                'user_id' => Auth::id(),
                'file_name' => $originalFileName,
                'file_type' => $extension,
                'file_path' => $info->file_path
            ]);
            
            return response()->download($filePath, $originalFileName);
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
            $tempFiles = [];

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
                foreach ($filteredFiles as $file) {
                    $studentName = $file->student->user->name ?? 'Unknown';

                    if ($file->file_url) {
                        try {
                            [$tempPath, $downloadName] = $this->prepareCloudinaryDownload($file, basename($file->file_path ?? $file->id));
                            $zip->addFile($tempPath, $studentName . '/' . $downloadName);
                            $tempFiles[] = $tempPath;
                        } catch (\Throwable $th) {
                            Log::warning('Skip file during Cloudinary batch download', [
                                'file_id' => $file->id,
                                'error' => $th->getMessage(),
                            ]);
                        }
                    } else {
                        $filePath = storage_path('app/public/' . $file->file_path);
                        if (file_exists($filePath)) {
                            $zip->addFile($filePath, $studentName . '/' . basename($filePath));
                        }
                    }
                }

                $zip->close();

                foreach ($tempFiles as $tempPath) {
                    @unlink($tempPath);
                }

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

    // View student files for teacher (lihat file yang diupload siswa)
    public function teacherViewStudentFiles(Request $r)
    {
        // Check if user is teacher
        $user = Auth::user();
        $isTeacher = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name','teacher')
            ->exists();

        abort_unless($isTeacher, 403, 'Hanya guru yang dapat mengakses halaman ini.');

        // Get all student files with relationships
        // Only from Kelas 10, 11, 12 (Anak Bangau)
        $q = InfoFile::with(['student.user', 'student.classRoom', 'student.attendances'])
            ->whereHas('student.classRoom', function($query) {
                $query->whereIn('grade', [10, 11, 12]);
            })
            ->latest();

        // Filter by class if provided
        if ($r->filled('class_room_id')) {
            $q->whereHas('student', function($query) use ($r) {
                $query->where('class_room_id', $r->class_room_id);
            });
        }

        // Filter by subject if provided
        if ($r->filled('subject')) {
            $q->where('subject', 'like', '%' . $r->subject . '%');
        }

        $files = $q->paginate(20)->withQueryString();

        // Get list of classes for filter (HANYA KELAS 10, 11, 12)
        $classRooms = \App\Models\ClassRoom::whereIn('grade', [10, 11, 12])
            ->orderBy('grade')
            ->orderBy('name')
            ->get();

        return view('info.teacher-view-files', compact('files', 'classRooms'));
    }

    /**
     * Calculate attendance percentage for a student
     * Presentase kehadiran dari semua jadwal (Lessons) berdasarkan absensi yang sudah direkap
     */
    public function getAttendancePercentage($studentId)
    {
        // Get all lessons from Anak Bangau (Kelas 10, 11, 12)
        $totalLessons = Lesson::whereHas('classRoom', function($query) {
            $query->whereIn('grade', [10, 11, 12]);
        })->count();

        if ($totalLessons == 0) {
            return ['percentage' => 0, 'present' => 0, 'total' => 0];
        }

        // Get attendance count (hadir/present only)
        $presentCount = \App\Models\Attendance::where('student_id', $studentId)
            ->whereIn('status', ['hadir', 'present', '1']) // sesuaikan dengan status di database
            ->count();

        // Calculate percentage
        $percentage = ($presentCount / $totalLessons) * 100;

        return [
            'percentage' => round($percentage, 2),
            'present' => $presentCount,
            'total' => $totalLessons,
            'formatted' => round($percentage, 2) . '%'
        ];
    }

    /**
     * Get attendance statistics for multiple students (untuk dashboard)
     */
    public function getStudentAttendanceStats($classRoomId = null)
    {
        $query = Student::query();

        if ($classRoomId) {
            $query->where('class_room_id', $classRoomId);
        } else {
            // Get from Kelas 10, 11, 12 only
            $query->whereHas('classRoom', function($q) {
                $q->whereIn('grade', [10, 11, 12]);
            });
        }

        $students = $query->with(['user', 'classRoom', 'attendances'])->get();

        $stats = $students->map(function($student) {
            $attendance = $this->getAttendancePercentage($student->id);
            return [
                'id' => $student->id,
                'name' => $student->user->name,
                'class' => $student->classRoom->name,
                'grade' => $student->classRoom->grade,
                'attendance' => $attendance['percentage'],
                'present' => $attendance['present'],
                'total' => $attendance['total'],
            ];
        });

        return $stats;
    }
}
