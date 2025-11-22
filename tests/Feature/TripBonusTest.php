<?php

namespace Tests\Feature;

use App\Http\Controllers\TripController;
use App\Models\Teacher;
use App\Models\TeacherTrip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TripBonusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2025-11-22 09:00:00');
    }

    public function test_store_accumulates_numeric_bonus(): void
    {
        $user = User::factory()->create();
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'employee_code' => 'EMP001',
        ]);

        $controller = app(TripController::class);

        // First submission with bonus 10 creates the record
        $request = Request::create('/dummy', 'POST', [
            'date' => Carbon::today()->toDateString(),
            'teaching_sessions' => 1,
            'bonus' => 10,
        ]);
        $controller->store($teacher, $request);

        $trip = TeacherTrip::first();
        $this->assertNotNull($trip);
        $this->assertSame(10, (int) $trip->bonus);
        $this->assertSame(1, (int) $trip->teaching_sessions);

        // Second submission accumulates bonus (+5)
        $secondRequest = Request::create('/dummy', 'POST', [
            'date' => Carbon::today()->toDateString(),
            'teaching_sessions' => 2,
            'bonus' => 5,
        ]);
        $controller->store($teacher, $secondRequest);

        $trip->refresh();
        $this->assertSame(15, (int) $trip->bonus);
        $this->assertSame(2, (int) $trip->teaching_sessions);
    }

    public function test_update_overrides_bonus(): void
    {
        $user = User::factory()->create();
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'employee_code' => 'EMP002',
        ]);

        $trip = TeacherTrip::create([
            'teacher_id' => $teacher->id,
            'date' => Carbon::today()->toDateString(),
            'teaching_sessions' => 1,
            'bonus' => 7,
        ]);

        $controller = app(TripController::class);

        $updateRequest = Request::create('/dummy', 'PUT', [
            'teaching_sessions' => 3,
            'bonus' => 12,
        ]);
        $controller->update($trip, $updateRequest);

        $trip->refresh();
        $this->assertSame(3, (int) $trip->teaching_sessions);
        $this->assertSame(12, (int) $trip->bonus);
    }
}
