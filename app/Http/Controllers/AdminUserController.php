<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\UserAudit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassRoom;
use App\Models\Teacher;

class AdminUserController extends Controller
{
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
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
			'is_approved' => true,
		]);

		try {
			if (method_exists($user, 'assignRole')) {
				$user->assignRole('teacher');
			}
		} catch (\Exception $e) {
			// ignore
		}

		// Ensure a Teacher record exists for this user so teacher routes work
		try {
			\App\Models\Teacher::firstOrCreate(['user_id' => $user->id]);
		} catch (\Exception $e) {
			Log::warning('Failed to create Teacher record for new user', ['error' => $e->getMessage()]);
		}

		return redirect()->route('admin.teachers.index')->with('success','Guru berhasil ditambahkan.');
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
		]);

		try {
			$user->name = $request->name;
			$user->email = $request->email;
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
		$query = Student::with(['user','classRoom.school'])->orderBy('id');

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
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
			'is_approved' => true, // admin-created accounts are approved
		]);

		try {
			if (method_exists($user, 'assignRole')) {
				$user->assignRole('student');
			}
		} catch (\Exception $e) {
			// ignore
		}

		try {
			Student::firstOrCreate([
				'user_id' => $user->id,
			], [
				'class_room_id' => $request->class_room_id,
				'nis' => $request->nis,
			]);
		} catch (\Exception $e) {
			Log::warning('Failed to create Student record for new user', ['error' => $e->getMessage()]);
		}

		return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan.');
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
