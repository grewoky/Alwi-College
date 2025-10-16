<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;
use App\Models\Student;

class PaymentController extends Controller
{
    // SISWA: form upload + riwayat
    public function index()
    {
        $student = Student::firstOrCreate(['user_id' => Auth::id()]);
        $payments = Payment::where('student_id', $student->id)->latest()->get();
        return view('payment.index', compact('payments'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'proof'        => 'required|mimes:jpg,jpeg,png,pdf|max:10240',
            'month_period' => 'nullable|date_format:Y-m',
            'amount'       => 'nullable|integer|min:0',
        ]);

        $student = Student::firstOrCreate(['user_id' => Auth::id()]);
        $path = $r->file('proof')->store('payment_proofs','public');

        Payment::create([
            'student_id'   => $student->id,
            'month_period' => $r->month_period,
            'amount'       => $r->amount,
            'proof_path'   => $path,
            'status'       => 'pending',
        ]);

        return back()->with('ok','Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    // ADMIN: daftar & verifikasi
    public function listAll(Request $r)
    {
        // filter ringan (opsional): status / bulan
        $q = Payment::with('student.user')->latest();
        if ($r->filled('status'))       $q->where('status', $r->status);
        if ($r->filled('month_period')) $q->where('month_period', $r->month_period);
        $payments = $q->paginate(20)->withQueryString();
        return view('payment.list', compact('payments'));
    }

    public function verify(Payment $payment, Request $r)
    {
        $r->validate(['status'=>'required|in:approved,rejected', 'note'=>'nullable|string|max:255']);

        $payment->update([
            'status' => $r->status,
            'note'   => $r->note,
        ]);

        return back()->with('ok','Status pembayaran diperbarui.');
    }

    // ADMIN: hapus record + file (kalau salah unggah)
    public function destroy(Payment $payment)
    {
        if ($payment->proof_path && Storage::disk('public')->exists($payment->proof_path)) {
            Storage::disk('public')->delete($payment->proof_path);
        }
        $payment->delete();
        return back()->with('ok','Data pembayaran dihapus.');
    }
}
