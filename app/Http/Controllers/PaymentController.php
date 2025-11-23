<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

    /**
     * Show edit page for a payment (admin)
     */
    public function edit(Payment $payment)
    {
        $payment->load('student.user');
        return view('payment.edit', compact('payment'));
    }
    public function verify(Payment $payment, Request $r)
    {
        try {
            $r->validate([
                'status' => 'required|in:approved,rejected',
                'note'   => 'nullable|string|max:255'
            ]);

            // Once a payment is approved, it should not be possible to change it to rejected.
            if ($payment->status === 'approved' && $r->status === 'rejected') {
                return back()->with('error', 'Pembayaran sudah disetujui; tidak dapat diubah menjadi Ditolak.');
            }

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

    /**
     * Serve payment proof file securely.
     * Accessible to admin or the student owner only.
     */
    public function showProof(Payment $payment)
    {

        $user = Auth::user();

        if (! $user) {
            abort(403, 'Unauthorized');
        }

        // ensure relation loaded
        $payment->loadMissing('student.user');

        // Check admin role via DB (avoids calling hasRole() method which static analyzers may flag)
        $isAdmin = DB::table('model_has_roles')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();

        // Allow if admin or owner
        if (! $isAdmin && $payment->student->user_id !== $user->id) {
            abort(403, 'Tidak diizinkan mengakses file ini.');
        }

        if (! $payment->proof_path || ! Storage::disk('public')->exists($payment->proof_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Build absolute path in storage/app/public
        $fullPath = storage_path('app/public/' . $payment->proof_path);
        if (! file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Use response()->file to serve the file (works on Windows and avoids symlink dependency)
        return response()->file($fullPath);
    }
}
