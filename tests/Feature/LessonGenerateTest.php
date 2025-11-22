<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\School;
use App\Models\ClassRoom;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Lesson;

class LessonGenerateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure roles exist
        if (! Role::where('name','admin')->exists()) {
            Role::create(['name'=>'admin']);
        }
    }

    public function test_generate_creates_single_lesson_per_date_per_grade()
    {
        // Create an admin user and give role
        $user = User::factory()->create();
        $user->assignRole('admin');
        $user = $user->fresh();

        // Create teacher
        $teacherUser = User::factory()->create();
        $teacher = Teacher::create(['user_id' => $teacherUser->id, 'employee_code' => 'T-' . Str::random(6)]);

        // Create school and a single grade-level class (no variant suffixes)
        // Allowed school names include: Negeri, IGS, Xavega, Bangau (and Kumbang)
        $school = School::create(['name' => 'Negeri']);
        $classA = ClassRoom::create(['school_id' => $school->id, 'name' => 'Kelas 10', 'grade' => 10]);

        // Post generate: expect it to create only one lesson per date across class variants
        $start = Carbon::today();
        $end = Carbon::today()->addDays(2);

        $resp = $this->actingAs($user)->post(route('lessons.generate'), [
            'school' => $school->name,
            'grade' => 10,
            'teacher_id' => $teacher->id,
            'subject_id' => null,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ]);

        $resp->assertSessionHas('ok');

        // There are 3 dates in range; we should have exactly 3 lessons total (one per date),
        // not 3 * number_of_variants.
        $this->assertEquals(3, Lesson::count(), "Expected 3 lessons (one per date) but found " . Lesson::count());

        // Running generate again with same params should not create additional lessons (idempotent)
        $resp2 = $this->actingAs($user)->post(route('lessons.generate'), [
            'school' => $school->name,
            'grade' => 10,
            'teacher_id' => $teacher->id,
            'subject_id' => null,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ]);

        $resp2->assertSessionHas('ok');
        $this->assertEquals(3, Lesson::count(), "Second generate should not increase lesson count");
    }
}
