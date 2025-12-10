<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\InfoFile;
use App\Models\Lesson;
use App\Models\Student;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use ZipArchive;

class InfoFileController extends Controller
{
    private function userHasRole(?object $user, string $role): bool
    {
        if (! $user) {
            return false;
        }

        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', $role)
            ->exists();
    }

    private function assertStudent(): Student
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized');
        abort_unless($this->userHasRole($user, 'student'), 403, 'Unauthorized');

        return Student::firstOrCreate(['user_id' => $user->id]);
    }

    private function assertAdmin(): void
    {
        $user = Auth::user();
        abort_unless($this->userHasRole($user, 'admin'), 403, 'Unauthorized');
    }

    private function assertTeacher(): void
    {
        $user = Auth::user();
        abort_unless($this->userHasRole($user, 'teacher'), 403, 'Unauthorized');
    }

    private function canAccessInfoFile(InfoFile $info, ?object $user): bool
    {
        if (! $user) {
            return false;
        }

        $info->loadMissing('student.user');

        return $this->userHasRole($user, 'admin')
            || $this->userHasRole($user, 'teacher')
            || optional($info->student)->user_id === $user->id;
    }

    public function index()
    {
        $student = $this->assertStudent();
        $files = InfoFile::where('student_id', $student->id)->latest()->get();

        return view('info.index', compact('files'));
    }

    public function store(Request $request)
    {
        $student = $this->assertStudent();

        $request->validate([
            'school' => 'nullable|max:120',
            'class_name' => 'nullable|max:50',
            'subject' => 'nullable|max:120',
            'title' => 'nullable|max:120',
            'material' => 'nullable|max:255',
            'cloudinary_public_id' => 'nullable|string|max:255',
            'cloudinary_secure_url' => 'nullable|url|max:2048',
            'cloudinary_format' => 'nullable|string|max:20',
            'cloudinary_original_filename' => 'nullable|string|max:255',
            'cloudinary_resource_type' => 'nullable|string|max:50',
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,txt,zip,rar,7z', 'max:10240'],
        ]);

        $hasDirectUpload = $request->filled('cloudinary_public_id') && $request->filled('cloudinary_secure_url');
        $hasFallbackFile = $request->hasFile('file');

        if (! $hasDirectUpload && ! $hasFallbackFile) {
            throw ValidationException::withMessages([
                'cloudinary_public_id' => 'Silakan unggah file melalui Cloudinary.',
            ]);
        }

        $cloudinaryId = null;
        $secureUrl = null;
        $extension = null;
        $storedFileName = null;

        try {
            if ($hasDirectUpload) {
                $cloudinaryId = $request->cloudinary_public_id;
                $secureUrl = $request->cloudinary_secure_url;
                $extension = strtolower((string) $request->input('cloudinary_format')) ?: null;
                $originalName = $request->input('cloudinary_original_filename') ?: basename($cloudinaryId);
                $sanitized = preg_replace('/[^a-zA-Z0-9.-]/', '_', (string) $originalName) ?: 'file';
                $storedFileName = $extension ? $sanitized . '.' . $extension : $sanitized;

                if (! $extension && $secureUrl) {
                    $path = parse_url($secureUrl, PHP_URL_PATH) ?: '';
                    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: null;
                    if ($extension) {
                        $storedFileName = $sanitized . '.' . $extension;
                    }
                }
            } else {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $baseName = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = pathinfo($originalName, PATHINFO_EXTENSION) ?: null;
                $sanitized = preg_replace('/[^a-zA-Z0-9.-]/', '_', $baseName) ?: 'file';
                $finalName = $sanitized . '_' . time();

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

                $upload = Cloudinary::uploadFile($file->getRealPath(), $options);
                $secureUrl = $upload->getSecurePath();
                $cloudinaryId = $upload->getPublicId();
                $storedFileName = $finalName . ($extension ? '.' . $extension : '');
            }

            if (! $storedFileName) {
                $basename = basename($cloudinaryId ?: 'file');
                $storedFileName = $extension ? $basename . '.' . $extension : $basename;
            }

            $title = $request->input('title') ?: pathinfo($storedFileName, PATHINFO_FILENAME);

            $infoFile = InfoFile::create([
                'student_id' => $student->id,
                'school' => $request->school,
                'class_name' => $request->class_name,
                'subject' => $request->subject,
                'title' => $title,
                'material' => $request->material,
                'file_path' => $storedFileName,
                'file_url' => $secureUrl,
                'file_public_id' => $cloudinaryId,
            ]);

            Log::info('Info file uploaded', [
                'student_id' => $student->id,
                'file_id' => $infoFile->id,
                'cloudinary_public_id' => $cloudinaryId,
                'direct_upload' => $hasDirectUpload,
            ]);

            return back()->with('ok', 'File berhasil diunggah.');
        } catch (\Throwable $th) {
            Log::error('Info file upload failed', [
                'user_id' => Auth::id(),
                'error' => $th->getMessage(),
            ]);

            return back()->with('error', 'Gagal mengunggah file: ' . $th->getMessage());
        }
    }

    public function destroy(InfoFile $info)
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized');

        $isOwner = optional($info->student)->user_id === $user->id;
        $isAdmin = $this->userHasRole($user, 'admin');

        abort_unless($isOwner || $isAdmin, 403, 'Unauthorized');

        try {
            if ($info->file_public_id) {
                try {
                    Cloudinary::destroy($info->file_public_id, ['invalidate' => true]);
                } catch (\Throwable $th) {
                    Log::warning('Cloudinary delete failed', [
                        'info_id' => $info->id,
                        'public_id' => $info->file_public_id,
                        'error' => $th->getMessage(),
                    ]);
                }
            }

            if ($info->file_path && Storage::disk('public')->exists($info->file_path)) {
                Storage::disk('public')->delete($info->file_path);
            }

            $info->delete();

            return back()->with('ok', 'File berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error('Info file delete failed', [
                'info_id' => $info->id,
                'error' => $th->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus file: ' . $th->getMessage());
        }
    }

    public function listAll()
    {
        $this->assertAdmin();

        $files = InfoFile::with(['student.user', 'student.classRoom'])->latest()->get();

        return view('info.list', compact('files'));
    }

    public function showDownloadOptions()
    {
        $this->assertAdmin();

        return view('info.download-options');
    }

    public function download(InfoFile $info)
    {
        $user = Auth::user();
        abort_unless($this->userHasRole($user, 'admin') || $this->userHasRole($user, 'teacher'), 403, 'Unauthorized.');

        return $this->streamInfoFile($info);
    }

    public function downloadWithDetails(InfoFile $info)
    {
        $user = Auth::user();
        abort_unless($this->userHasRole($user, 'admin') || $this->userHasRole($user, 'teacher'), 403, 'Unauthorized.');

        return $this->streamInfoFile($info, true);
    }

    public function downloadByType(Request $request)
    {
        $this->assertAdmin();

        $type = $request->input('type');
        if (! $type) {
            return back()->with('error', 'Tipe file tidak ditentukan');
        }

        $files = InfoFile::with('student.user')->get()->filter(function (InfoFile $file) use ($type) {
            $extension = $this->getFileExtension($file->file_path ?? '');
            return strcasecmp($this->getFileType($extension), $type) === 0;
        });

        if ($files->isEmpty()) {
            return back()->with('error', 'Tidak ada file tipe ' . $type);
        }

        return $this->zipAndDownload($files, 'files-' . Str::slug($type) . '-' . now()->format('Ymd-His') . '.zip');
    }

    public function downloadSelected(Request $request)
    {
        $this->assertAdmin();

        $ids = $request->input('file_ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu file.');
        }

        $files = InfoFile::with('student.user')->whereIn('id', $ids)->get();
        if ($files->isEmpty()) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return $this->zipAndDownload($files, 'selected-files-' . now()->format('Ymd-His') . '.zip');
    }

    public function downloadAll()
    {
        $this->assertAdmin();

        $files = InfoFile::with('student.user')->get();
        if ($files->isEmpty()) {
            return back()->with('error', 'Tidak ada file untuk didownload');
        }

        return $this->zipAndDownload($files, 'info-files-' . now()->format('Ymd-His') . '.zip');
    }

    public function getFileStats()
    {
        $this->assertAdmin();

        $files = InfoFile::all();
        $stats = [
            'total' => $files->count(),
            'byType' => [],
            'bySize' => 0,
        ];

        foreach ($files as $file) {
            $extension = $this->getFileExtension($file->file_path ?? '');
            $type = $this->getFileType($extension);
            $stats['byType'][$type] = ($stats['byType'][$type] ?? 0) + 1;

            $localPath = $file->file_path ? storage_path('app/public/' . $file->file_path) : null;
            if ($localPath && is_file($localPath)) {
                $stats['bySize'] += filesize($localPath);
            }
        }

        $stats['bySize'] = round($stats['bySize'] / (1024 * 1024), 2);

        return response()->json($stats);
    }

    public function teacherViewStudentFiles(Request $request)
    {
        $this->assertTeacher();

        $query = InfoFile::with(['student.user', 'student.classRoom', 'student.attendances'])
            ->whereHas('student.classRoom', function ($q) {
                $q->whereIn('grade', [10, 11, 12]);
            })
            ->latest();

        if ($request->filled('class_room_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_room_id', $request->class_room_id);
            });
        }

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }

        $files = $query->paginate(20)->withQueryString();
        $classRooms = ClassRoom::whereIn('grade', [10, 11, 12])
            ->orderBy('grade')
            ->orderBy('name')
            ->get();

        return view('info.teacher-view-files', compact('files', 'classRooms'));
    }

    public function showFile(InfoFile $info)
    {
        $user = Auth::user();
        abort_unless($this->canAccessInfoFile($info, $user), 403, 'Tidak diizinkan mengakses file ini.');
        if ($info->file_url) {
            return redirect()->away($info->file_url);
        }

        if (! $info->file_path || ! Storage::disk('public')->exists($info->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $info->file_path);
        if (! is_file($fullPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($fullPath);
    }

    public function getAttendancePercentage($studentId)
    {
        $totalLessons = Lesson::whereHas('classRoom', function ($query) {
            $query->whereIn('grade', [10, 11, 12]);
        })->count();

        if ($totalLessons === 0) {
            return ['percentage' => 0, 'present' => 0, 'total' => 0];
        }

        $presentCount = Attendance::where('student_id', $studentId)
            ->whereIn('status', ['hadir', 'present', '1'])
            ->count();

        $percentage = ($presentCount / $totalLessons) * 100;

        return [
            'percentage' => round($percentage, 2),
            'present' => $presentCount,
            'total' => $totalLessons,
            'formatted' => round($percentage, 2) . '%',
        ];
    }

    public function getStudentAttendanceStats($classRoomId = null)
    {
        $query = Student::query();

        if ($classRoomId) {
            $query->where('class_room_id', $classRoomId);
        } else {
            $query->whereHas('classRoom', function ($q) {
                $q->whereIn('grade', [10, 11, 12]);
            });
        }

        $students = $query->with(['user', 'classRoom', 'attendances'])->get();

        return $students->map(function (Student $student) {
            $attendance = $this->getAttendancePercentage($student->id);

            return [
                'id' => $student->id,
                'name' => $student->user->name,
                'class' => optional($student->classRoom)->name,
                'grade' => optional($student->classRoom)->grade,
                'attendance' => $attendance['percentage'],
                'present' => $attendance['present'],
                'total' => $attendance['total'],
            ];
        });
    }

    private function streamInfoFile(InfoFile $info, bool $logDetails = false)
    {
        if ($info->file_url) {
            try {
                [$tempPath, $downloadName] = $this->prepareCloudinaryDownload($info);

                if ($logDetails) {
                    Log::info('Info file downloaded (Cloudinary)', [
                        'info_id' => $info->id,
                        'user_id' => Auth::id(),
                        'file_name' => $downloadName,
                        'public_id' => $info->file_public_id,
                    ]);
                }

                return response()->download($tempPath, $downloadName)->deleteFileAfterSend(true);
            } catch (\Throwable $th) {
                Log::error('Info file download failed (Cloudinary)', [
                    'info_id' => $info->id,
                    'error' => $th->getMessage(),
                ]);

                return back()->with('error', 'Gagal mengunduh file dari Cloudinary.');
            }
        }

        if (! $info->file_path || ! Storage::disk('public')->exists($info->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $info->file_path);

        if ($logDetails) {
            Log::info('Info file downloaded (local storage)', [
                'info_id' => $info->id,
                'user_id' => Auth::id(),
                'file_path' => $info->file_path,
            ]);
        }

        return response()->download($fullPath, basename($fullPath));
    }

    private function zipAndDownload($files, string $zipFileName)
    {
        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipPath = $tempDir . '/' . $zipFileName;
        $zip = new ZipArchive();
        $tempFiles = [];

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }

        foreach ($files as $file) {
            $studentName = optional(optional($file->student)->user)->name ?: 'Unknown';

            if ($file->file_url) {
                try {
                    [$tempPath, $downloadName] = $this->prepareCloudinaryDownload($file);
                    $zip->addFile($tempPath, $studentName . '/' . $downloadName);
                    $tempFiles[] = $tempPath;
                } catch (\Throwable $th) {
                    Log::warning('Skip Cloudinary file during zip build', [
                        'info_id' => $file->id,
                        'error' => $th->getMessage(),
                    ]);
                }
            } else {
                $localPath = $file->file_path ? storage_path('app/public/' . $file->file_path) : null;
                if ($localPath && is_file($localPath)) {
                    $zip->addFile($localPath, $studentName . '/' . basename($localPath));
                }
            }
        }

        $zip->close();

        foreach ($tempFiles as $path) {
            @unlink($path);
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
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

        if (! str_contains($fileName, '.') && $info->file_path) {
            $extension = pathinfo($info->file_path, PATHINFO_EXTENSION);
            if ($extension) {
                $fileName .= '.' . $extension;
            }
        }

        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir . '/' . Str::uuid() . '_' . $fileName;

        $response = Http::timeout(45)->sink($tempPath)->get($info->file_url);
        if (! $response->successful()) {
            @unlink($tempPath);
            throw new \RuntimeException('Gagal mengambil file dari Cloudinary.');
        }

        if (! is_file($tempPath)) {
            throw new \RuntimeException('File sementara tidak ditemukan.');
        }

        return [$tempPath, $fileName];
    }

    private function getFileExtension(string $filePath): string
    {
        return strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    }

    private function getFileType(string $extension): string
    {
        $extension = strtolower($extension);

        $imageExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        $docExt = ['pdf', 'doc', 'docx', 'txt'];
        $spreadsheetExt = ['xls', 'xlsx'];
        $presentationExt = ['ppt', 'pptx'];
        $archiveExt = ['zip', 'rar', '7z'];

        if (in_array($extension, $imageExt, true)) {
            return 'Gambar';
        }

        if (in_array($extension, $docExt, true)) {
            return 'Dokumen';
        }

        if (in_array($extension, $spreadsheetExt, true)) {
            return 'Spreadsheet';
        }

        if (in_array($extension, $presentationExt, true)) {
            return 'Presentasi';
        }

        if (in_array($extension, $archiveExt, true)) {
            return 'Arsip';
        }

        return 'File Lainnya';
    }
}
