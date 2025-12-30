<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * SISWA: form upload + riwayat
     */
    public function index()
    {
        $student = Student::firstOrCreate(['user_id' => Auth::id()]);
        $payments = Payment::where('student_id', $student->id)->latest()->get();

        return view('payment.index', compact('payments'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'month_period' => 'nullable|date_format:Y-m',
            'amount' => 'nullable|integer|min:0',
            'cloudinary_public_id' => 'nullable|string|max:255',
            'cloudinary_secure_url' => 'nullable|url|max:2048',
            'cloudinary_format' => 'nullable|string|max:20',
            'cloudinary_original_filename' => 'nullable|string|max:255',
            'cloudinary_resource_type' => 'nullable|string|max:50',
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        $hasDirectUpload = $r->filled('cloudinary_public_id') && $r->filled('cloudinary_secure_url');

        if (! $hasDirectUpload && ! $r->hasFile('proof')) {
            throw ValidationException::withMessages([
                'proof' => 'Silakan unggah bukti pembayaran melalui Cloudinary.',
            ]);
        }

        $student = Student::firstOrCreate(['user_id' => Auth::id()]);

        $cloudinaryId = null;
        $secureUrl = null;

        if ($hasDirectUpload) {
            $cloudinaryId = $r->cloudinary_public_id;
            $secureUrl = $r->cloudinary_secure_url;
        } else {
            $this->enforceImageSizeLimit($r, ['proof']);

            $file = $r->file('proof');
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $folder = 'payments/' . $student->id;
            $publicId = Str::slug($baseName . '-' . Str::random(8));

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
        }

        Payment::create([
            'student_id' => $student->id,
            'month_period' => $r->month_period,
            'amount' => $r->amount,
            'proof_path' => $cloudinaryId,
            'proof_url' => $secureUrl,
            'proof_public_id' => $cloudinaryId,
            'status' => 'pending',
        ]);

        return back()->with('ok', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    /**
     * ADMIN: daftar & verifikasi
     */
    public function listAll(Request $r)
    {
        $query = Payment::with('student.user')->latest();

        if ($r->filled('status')) {
            $query->where('status', $r->status);
        }

        if ($r->filled('month_period')) {
            $query->where('month_period', $r->month_period);
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('payment.list', compact('payments'));
    }

    public function edit(Payment $payment)
    {
        $payment->load('student.user');

        return view('payment.edit', compact('payment'));
    }

    public function verify(Payment $payment, Request $r)
    {
        $r->validate([
            'status' => 'required|in:approved,rejected',
            'note' => 'nullable|string|max:255',
            'month_period' => 'nullable|date_format:Y-m',
            'amount' => 'nullable|integer|min:0',
            'cloudinary_public_id' => 'nullable|string|max:255',
            'cloudinary_secure_url' => 'nullable|url|max:2048',
            'cloudinary_format' => 'nullable|string|max:20',
            'cloudinary_original_filename' => 'nullable|string|max:255',
            'cloudinary_resource_type' => 'nullable|string|max:50',
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        if ($payment->status === 'approved' && $r->status === 'rejected') {
            return back()->with('error', 'Pembayaran sudah disetujui; tidak dapat diubah menjadi Ditolak.');
        }

        $hasDirectUpload = $r->filled('cloudinary_public_id') && $r->filled('cloudinary_secure_url');
        $hasFallbackFile = $r->hasFile('proof');

        if ($hasFallbackFile) {
            $this->enforceImageSizeLimit($r, ['proof']);
        }

        $newCloudinaryId = null;
        $newSecureUrl = null;

        if ($hasDirectUpload) {
            $newCloudinaryId = $r->cloudinary_public_id;
            $newSecureUrl = $r->cloudinary_secure_url;
        } elseif ($hasFallbackFile) {
            $file = $r->file('proof');
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $folder = 'payments/' . ($payment->student_id ?: 'misc');
            $publicId = Str::slug($baseName . '-' . Str::random(8));

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
            $newSecureUrl = $upload->getSecurePath();
            $newCloudinaryId = $upload->getPublicId();
        }

        if ($newCloudinaryId && $payment->proof_url) {
            $this->deleteExistingProof($payment);
        }

        $payment->update(array_filter([
            'status' => $r->status,
            'note' => $r->note,
            'month_period' => $r->month_period,
            'amount' => $r->amount,
            'proof_path' => $newCloudinaryId ? $newCloudinaryId : $payment->proof_path,
            'proof_url' => $newSecureUrl ? $newSecureUrl : $payment->proof_url,
            'proof_public_id' => $newCloudinaryId ? $newCloudinaryId : $payment->proof_public_id,
        ], fn ($value) => $value !== null));

        return back()->with('ok', 'Status pembayaran diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        try {
            $this->deleteExistingProof($payment);

            $studentName = optional($payment->student)->user->name ?? 'Unknown';
            $payment->delete();

            Log::info('Payment deleted', [
                'payment_id' => $payment->id,
                'student_id' => $payment->student_id,
                'student_name' => $studentName,
            ]);

            return redirect()->route('pay.list')->with('ok', 'Data pembayaran dihapus.');
        } catch (\Throwable $th) {
            Log::error('Payment delete failed', [
                'payment_id' => $payment->id,
                'error' => $th->getMessage(),
            ]);

            return redirect()->route('pay.list')->with('error', 'Gagal menghapus data pembayaran');
        }
    }

    /**
     * Serve payment proof file securely.
     */
    public function showProof(Payment $payment)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Unauthorized');
        }

        $payment->loadMissing('student.user');

        $isAdmin = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();

        if (! $isAdmin && optional($payment->student)->user_id !== $user->id) {
            abort(403, 'Tidak diizinkan mengakses file ini.');
        }

        if ($payment->proof_url) {
            return redirect()->away($payment->proof_url);
        }

        if (! $payment->proof_path || ! Storage::disk('public')->exists($payment->proof_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $payment->proof_path);

        if (! file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($fullPath);
    }

    private function deleteExistingProof(Payment $payment): void
    {
        $cloudinaryId = $payment->proof_public_id ?: $payment->proof_path;

        if ($cloudinaryId) {
            try {
                Cloudinary::destroy($cloudinaryId, ['invalidate' => true]);
            } catch (\Throwable $th) {
                Log::warning('Failed to delete payment proof from Cloudinary', [
                    'payment_id' => $payment->id,
                    'public_id' => $cloudinaryId,
                    'error' => $th->getMessage(),
                ]);
            }
        }

        if ($payment->proof_path && Storage::disk('public')->exists($payment->proof_path)) {
            Storage::disk('public')->delete($payment->proof_path);
        }
    }
}
