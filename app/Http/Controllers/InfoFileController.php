<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfoFile;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;      // ⬅️ penting

class InfoFileController extends Controller
{
    // Halaman pelajar: form upload + daftar file miliknya
    public function index()
    {
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
