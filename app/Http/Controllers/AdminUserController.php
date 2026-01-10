<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\UserAudit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCreatedNotification;
use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Services\ResendService;

class AdminUserController extends Controller
{
	protected ResendService $resendService;

	public function __construct(ResendService $resendService)
	{
		$this->resendService = $resendService;
	}
	public function pending()
	{
		$users = User::where('is_approved', false)->orderBy('created_at','desc')->get();
		return view('admin.pending_users', compact('users'));
	}

	public function approve(User $user)
	{
		$user->is_approved = true;
		$user->save();

		return redirect()->back()->with('success', 'User telah diverifikasi.');
	}

	// show form to create a teacher account
	public function createTeacher()
	{
		return view('admin.create_teacher');
	}

	// store teacher account
	public function storeTeacher(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string|min:8|confirmed',
			'phone' => 'nullable|string|max:30|unique:users,phone',
			'is_active' => 'nullable|in:0,1',
		]);

		try {
			DB::beginTransaction();

			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'password' => bcrypt($request->password),
				'is_approved' => true,
				'is_active' => (bool) $request->input('is_active', true),
				'phone' => $request->phone ?? null,
			]);

			try {
				if (method_exists($user, 'assignRole')) {
					$user->assignRole('teacher');
				}
			} catch (Throwable $e) {
				Log::warning('Failed to assign teacher role', ['user_id' => $user->id, 'error' => $e->getMessage()]);
			}

			// Ensure a Teacher record exists for this user so teacher routes work
			\App\Models\Teacher::firstOrCreate(['user_id' => $user->id]);

			DB::commit();
		} catch (Throwable $e) {
			try {
				DB::rollBack();
			} catch (Throwable $rollbackError) {
				// ignore
			}

			Log::error('Failed to store teacher', ['error' => $e->getMessage()]);
			return redirect()->back()->withErrors('Gagal menambahkan pengajar.');
		}

		// Send account creation email notification via ResendService
		$emailHtml = '<p>Halo ' . htmlspecialchars($user->name) . ',</p>'
			. '<p>Akun guru Anda telah dibuat oleh admin dengan detail berikut:</p>'
			. '<ul>'
			. '<li><strong>Email:</strong> ' . htmlspecialchars($user->email) . '</li>'
			. '<li><strong>Password:</strong> ' . htmlspecialchars($request->password) . '</li>'
			. '</ul>'
			. '<p>Silakan login ke dashboard dengan kredensial di atas. Anda dapat mengubah password Anda setelah login.</p>'
			. '<p>Terima kasih,<br/>Tim Alwi College</p>';

		$this->resendService->sendEmail(
			$user->email,
			'Akun Guru Dibuat',
			$emailHtml
		);

		return redirect()->route('admin.teachers.index')->with('success','Guru berhasil ditambahkan dan email notifikasi telah dikirim.');
	}

	/**
	 * List all teachers for admin management
	 */
	public function teachersIndex()
	{
		$teachers = \App\Models\Teacher::with('user')->orderBy('id')->paginate(20);
		return view('admin.teachers_index', compact('teachers'));
	}

	/**
	 * Show the edit form for a teacher
	 */
	public function editTeacher(Teacher $teacher)
	{
		return view('admin.edit_teacher', compact('teacher'));
	}

	/**
	 * Update teacher user/profile
	 */
	public function updateTeacher(Request $request, Teacher $teacher)
	{
		$user = $teacher->user;
		if (!$user) {
			return redirect()->back()->withErrors('User for this teacher not found.');
		}

		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email,' . $user->id,
			'employee_code' => 'nullable|string|max:50',
			'phone' => 'nullable|string|max:30|unique:users,phone,' . $user->id,
			'is_approved' => 'nullable|in:0,1',
			'is_active' => 'nullable|in:0,1',
		]);

		try {
			$user->name = $request->name;
			$user->email = $request->email;
			$user->phone = $request->phone ?? null;
			if ($request->filled('is_approved')) {
				$user->is_approved = (bool) $request->is_approved;
			}
			if ($request->filled('is_active')) {
				$user->is_active = (bool) $request->is_active;
			}
			$user->save();

			$teacher->employee_code = $request->employee_code;
			$teacher->save();

			return redirect()->route('admin.teachers.index')->with('success','Data pengajar berhasil diperbarui.');
		} catch (\Exception $e) {
			Log::error('Failed to update teacher', ['error' => $e->getMessage()]);
			return redirect()->back()->withErrors('Gagal memperbarui data pengajar.');
		}
	}

	/**
	 * Delete a teacher account (and user)
	 */
	public function destroyTeacher(\App\Models\Teacher $teacher)
	{
		$user = $teacher->user;
		if ($user) {
			try {
				// audit
				try {
					UserAudit::create([
						'action' => 'delete_teacher',
						'target_user_id' => $user->id,
						'target_student_id' => null,
						'performed_by' => Auth::id(),
						'details' => json_encode(['teacher_id' => $teacher->id, 'user_email' => $user->email]),
					]);
				} catch (\Exception $e) {
					Log::warning('Failed to write user audit for delete teacher', ['error' => $e->getMessage()]);
				}

				$user->delete();
				return redirect()->back()->with('success','Akun pengajar berhasil dihapus.');
			} catch (\Exception $e) {
				Log::error('Failed to delete teacher account', ['error' => $e->getMessage()]);
				return redirect()->back()->withErrors('Gagal menghapus akun pengajar.');
			}
		}

		try {
			$teacher->delete();
			return redirect()->back()->with('success','Data pengajar berhasil dihapus.');
		} catch (\Exception $e) {
			Log::error('Failed to delete teacher record', ['error' => $e->getMessage()]);
			return redirect()->back()->withErrors('Gagal menghapus data pengajar.');
		}
	}

	/**
	 * Clear the email address of a student's user account (generate a unique placeholder).
	 * POST /admin/students/{student}/clear-email
	 */
	public function clearStudentEmail(Student $student)
	{
		$user = $student->user;
		if (!$user) {
			return redirect()->back()->withErrors('User untuk siswa ini tidak ditemukan.');
		}

		// Generate a unique placeholder email to avoid unique constraint issues
		$placeholder = 'removed_user_' . $user->id . '_' . time() . '@example.invalid';

		try {
			$old = $user->email;
			$user->email = $placeholder;
			$user->email_verified_at = null;
			$user->save();

			Log::info('Admin cleared student email', ['student_id' => $student->id, 'user_id' => $user->id, 'old_email' => $old, 'new_email' => $placeholder]);

			// record audit
			try {
				UserAudit::create([
					'action' => 'clear_email',
					'target_user_id' => $user->id,
					'target_student_id' => $student->id,
					'performed_by' => Auth::id(),
					'details' => json_encode(['old_email' => $old, 'new_email' => $placeholder]),
				]);
			} catch (\Exception $e) {
				Log::warning('Failed to write user audit for clear email', ['error' => $e->getMessage()]);
			}

			return redirect()->back()->with('success', 'Email siswa berhasil dihapus (digantikan placeholder).');
		} catch (\Exception $e) {
			Log::error('Failed to clear student email', ['error' => $e->getMessage()]);
			return redirect()->back()->withErrors('Gagal menghapus email siswa.');
		}
	}

	/**
	 * List all students for admin management
	 */
	public function studentsIndex()
	{
		$r = request();
		// Order students by associated user's name (alphabetical)
		$query = Student::select('students.*')
			->leftJoin('users','users.id','students.user_id')
			->with(['user','classRoom.school'])
			->orderBy('users.name');

		if ($r->filled('q')) {
			$q = $r->q;
			$query->where(function($qq) use ($q) {
				$qq->whereHas('user', function($u) use ($q) {
					$u->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
				})->orWhere('nis', 'like', "%{$q}%");
			});
		}

		$students = $query->paginate(20)->withQueryString();
		$classRooms = ClassRoom::with('school')->orderBy('grade')->orderBy('name')->get();
		return view('admin.students_index', compact('students','classRooms'));
	}

	/**
	 * Show form to edit a student and user profile
	 */
	public function editStudent(Student $student)
	{
		$classRooms = ClassRoom::with('school')->orderBy('grade')->orderBy('name')->get();
		return view('admin.edit_student', compact('student','classRooms'));
	}

	/**
	 * Update student and associated user (name, email, class, nis, active)
	 */
	public function updateStudent(Request $request, Student $student)
	{
		$user = $student->user;
		if (!$user) return redirect()->back()->withErrors('User for this student not found.');

		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email,' . $user->id,
			'class_room_id' => 'nullable|exists:class_rooms,id',
			'nis' => 'nullable|string|max:50',
			'phone' => 'nullable|string|max:30|unique:users,phone,' . $user->id,
			'is_approved' => 'nullable|in:0,1',
			'is_active' => 'nullable|in:0,1',
		]);

		try {
			$user->name = $request->name;
			$user->email = $request->email;
			$user->phone = $request->phone ?? null;
			if ($request->filled('is_approved')) {
				$user->is_approved = (bool) $request->is_approved;
			}
			if ($request->filled('is_active')) {
				$user->is_active = (bool) $request->is_active;
			}
			$user->save();

			$student->class_room_id = $request->class_room_id;
			$student->nis = $request->nis;
			$student->save();

			return redirect()->route('admin.students.index')->with('success','Data siswa berhasil diperbarui.');
		} catch (\Exception $e) {
			Log::error('Failed to update student', ['error' => $e->getMessage()]);
			return redirect()->back()->withErrors('Gagal memperbarui data siswa.');
		}
	}

	/**
	 * Show form to create a student account by admin
	 */
	public function createStudent()
	{
		$classRooms = ClassRoom::with('school')->orderBy('grade')->orderBy('name')->get();
		return view('admin.create_student', compact('classRooms'));
	}

	/**
	 * Store a student account created by admin
	 */
	public function storeStudent(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string|min:8|confirmed',
			'class_room_id' => 'nullable|exists:class_rooms,id',
			'nis' => 'nullable|string|max:50',
			'phone' => 'nullable|string|max:30|unique:users,phone',
			'is_active' => 'nullable|in:0,1',
		]);

		try {
			DB::beginTransaction();

			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'password' => bcrypt($request->password),
				'is_approved' => true, // admin-created accounts are approved
				'is_active' => (bool) $request->input('is_active', true),
				'phone' => $request->phone ?? null,
			]);

			try {
				if (method_exists($user, 'assignRole')) {
					$user->assignRole('student');
				}
			} catch (Throwable $e) {
				Log::warning('Failed to assign student role', ['user_id' => $user->id, 'error' => $e->getMessage()]);
			}

			Student::firstOrCreate([
				'user_id' => $user->id,
			], [
				'class_room_id' => $request->class_room_id,
				'nis' => $request->nis,
			]);

			DB::commit();
		} catch (Throwable $e) {
			try {
				DB::rollBack();
			} catch (Throwable $rollbackError) {
				// ignore
			}

			Log::error('Failed to store student', ['error' => $e->getMessage()]);
			return redirect()->back()->withErrors('Gagal menambahkan siswa.');
		}

		// Send account creation email notification via ResendService
		$emailHtml = '<p>Halo ' . htmlspecialchars($user->name) . ',</p>'
			. '<p>Akun siswa Anda telah dibuat oleh admin dengan detail berikut:</p>'
			. '<ul>'
			. '<li><strong>Email:</strong> ' . htmlspecialchars($user->email) . '</li>'
			. '<li><strong>Password:</strong> ' . htmlspecialchars($request->password) . '</li>'
			. '</ul>'
			. '<p>Silakan login ke dashboard dengan kredensial di atas. Anda dapat mengubah password Anda setelah login.</p>'
			. '<p>Terima kasih,<br/>Tim Alwi College</p>';

		$this->resendService->sendEmail(
			$user->email,
			'Akun Siswa Dibuat',
			$emailHtml
		);

		return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan dan email notifikasi telah dikirim.');
	}

	/**
	 * Delete a student's account (and user) — admin action
	 */
	public function destroyStudent(Student $student)
	{
		$user = $student->user;
		if ($user) {
			try {
				// record audit before deletion
				try {
					UserAudit::create([
						'action' => 'delete_account',
						'target_user_id' => $user->id,
						'target_student_id' => $student->id,
						'performed_by' => Auth::id(),
						'details' => json_encode(['student_id' => $student->id, 'user_name' => $user->name, 'user_email' => $user->email]),
					]);
				} catch (\Exception $e) {
					Log::warning('Failed to write user audit for delete account', ['error' => $e->getMessage()]);
				}

				$user->delete(); // cascading should remove Student via FK
				Log::info('Admin deleted student account', ['student_id' => $student->id, 'user_id' => $user->id]);
				return redirect()->back()->with('success', 'Akun siswa berhasil dihapus.');
			} catch (\Exception $e) {
				Log::error('Failed to delete student account', ['error' => $e->getMessage()]);
				return redirect()->back()->withErrors('Gagal menghapus akun siswa.');
			}
		}

		// If no user (shouldn't happen), just delete student record but still audit
		try {
			try {
				UserAudit::create([
					'action' => 'delete_student_record',
					'target_user_id' => null,
					'target_student_id' => $student->id,
					'performed_by' => Auth::id(),
					'details' => json_encode(['student_id' => $student->id]),
				]);
			} catch (\Exception $e) {
				Log::warning('Failed to write user audit for delete student record', ['error' => $e->getMessage()]);
			}

			$student->delete();
			return redirect()->back()->with('success', 'Data siswa berhasil dihapus.');
		} catch (\Exception $e) {
			Log::error('Failed to delete student record', ['error' => $e->getMessage()]);
			return redirect()->back()->withErrors('Gagal menghapus data siswa.');
		}
	}
}
