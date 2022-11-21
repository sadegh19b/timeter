<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Time;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected array $requestData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestData = [
            'start_at' => now()->format('Y-m-d H:i'),
            'end_at' => now()->addHour()->format('Y-m-d H:i')
        ];
    }

    public function test_user_can_store_a_new_time_for_project(): void
    {
        $project = Project::factory()->create();

        $this->post(route('times.store', $project), $this->requestData)
            ->assertOk();

        $this->normalizeTime();

        $this->assertDatabaseHas('times', $this->requestData);
        $this->assertDatabaseCount('times', 1);
    }

    public function test_user_can_store_a_new_time_for_project_without_end_at_filled(): void
    {
        $project = Project::factory()->create();
        $this->requestData['end_at'] = null;

        $this->post(route('times.store', $project), $this->requestData)
            ->assertOk();

        $this->normalizeTime(true);

        $this->assertDatabaseHas('times', $this->requestData);
        $this->assertDatabaseCount('times', 1);
    }

    public function test_user_can_store_a_new_time_for_project_in_persian_date_format(): void
    {
        $project = Project::factory()->create();
        $requestData = [
            'start_at' => verta()->format('Y-m-d H:i'),
            'end_at' => verta()->addHour()->format('Y-m-d H:i')
        ];

        $this->post(route('times.store', $project), $requestData)
            ->assertOk();

        $this->normalizeTime();

        $this->assertDatabaseHas('times', $this->requestData);
        $this->assertDatabaseCount('times', 1);
    }

    public function test_user_can_update_a_time(): void
    {
        $time = Time::factory()->fulfil()->create();

        $this->put(route('times.update', $time), $this->requestData)
            ->assertOk();

        $this->normalizeTime();

        $this->assertDatabaseHas('times', $this->requestData + ['id' => $time->id]);
    }

    public function test_user_can_update_a_time_without_end_at_filled(): void
    {
        $time = Time::factory()->fulfil()->create();
        $this->requestData['end_at'] = null;

        $this->put(route('times.update', $time), $this->requestData)
            ->assertOk();

        $this->normalizeTime(true);

        $this->assertDatabaseHas('times', $this->requestData + ['id' => $time->id]);
    }

    public function test_user_can_destroy_a_time(): void
    {
        $time = Time::factory()->create();

        // Soft delete
        $this->delete(route('times.destroy', $time))
            ->assertOk();

        $this->assertSoftDeleted($time);

        // Force delete
        $this->delete(route('times.destroy_permanent', $time))
            ->assertOk();

        $this->assertModelMissing($time);
    }

    public function test_user_can_restore_a_soft_deleted_time(): void
    {
        $time = Time::factory()->create(['deleted_at' => now()]);

        $this->get(route('times.restore', $time))
            ->assertOk();

        $this->assertNotSoftDeleted($time);
    }

    private function normalizeTime($onlyStartAt = false): void
    {
        $this->requestData['start_at'] .= ':00';

        if (!$onlyStartAt) {
            $this->requestData['end_at'] .= ':00';
        }
    }
}
