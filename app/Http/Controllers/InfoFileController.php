<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfoFile;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;  
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;    // ⬅️ penting

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
    // Hapus file fisik (jika ada)
    if ($info->file_path && Storage::disk('public')->exists($info->file_path)) {
        Storage::disk('public')->delete($info->file_path);
    }

    // Hapus record di DB
    $info->delete();

    return back()->with('ok', 'File berhasil dihapus.');
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
        $r->validate([
            'title' => 'required|max:120',
            'file'  => 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $student = Student::firstOrCreate(['user_id' => Auth::id()]); // ⬅️ pakai Auth::id()
        $path = $r->file('file')->store('info_files', 'public');

        InfoFile::create([
            'student_id' => $student->id,
            'title'      => $r->title,
            'file_path'  => $path,
        ]);

        return back()->with('ok', 'File berhasil diunggah!');
    }

    // Halaman guru/admin: lihat semua file pelajar
    public function listAll()
    {
        $files = InfoFile::with('student.user')->latest()->get();
        return view('info.list', compact('files'));
    }

    // Guru/admin download file tertentu
    public function download(InfoFile $info)
    {
        return response()->download(storage_path('app/public/'.$info->file_path));
    }
}
