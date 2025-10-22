<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
        try {
            $r->validate([
                'status' => 'required|in:approved,rejected',
                'note'   => 'nullable|string|max:255'
            ]);

            $oldStatus = $payment->status;
            
            $payment->update([
                'status' => $r->status,
                'note'   => $r->note,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            $studentName = $payment->student->user->name ?? 'Unknown';
            $action = $r->status === 'approved' ? 'APPROVED' : 'REJECTED';
            
            Log::info("Payment {$action} for {$studentName} (was: {$oldStatus})", [
                'payment_id' => $payment->id,
                'student_id' => $payment->student_id,
                'status' => $r->status,
            ]);

            return back()->with('ok', "Status pembayaran berhasil diperbarui menjadi {$r->status}.");
        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);
            return back()->with('error', 'Gagal memperbarui status pembayaran');
        }
    }

    // ADMIN: hapus record + file (kalau salah unggah)
    public function destroy(Payment $payment)
    {
        try {
            // Delete file if exists
            if ($payment->proof_path && Storage::disk('public')->exists($payment->proof_path)) {
                Storage::disk('public')->delete($payment->proof_path);
            }
            
            $studentName = $payment->student->user->name ?? 'Unknown';
            $payment->delete();
            
            Log::info("Payment deleted for student: {$studentName}", [
                'payment_id' => $payment->id,
                'student_id' => $payment->student_id,
            ]);
            
            return back()->with('ok','Data pembayaran dihapus.');
        } catch (\Exception $e) {
            Log::error('Payment delete failed', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);
            return back()->with('error', 'Gagal menghapus data pembayaran');
        }
    }
}
