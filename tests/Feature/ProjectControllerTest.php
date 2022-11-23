<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected array $requestData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestData = Project::factory()->fulfil()->make()->toArray();
    }

    /** @test */
    public function user_can_store_a_new_project(): void
    {
        $this->post(route('projects.store'), $this->requestData)
            ->assertOk();

        $this->assertDatabaseHas('projects', $this->requestData);
        $this->assertDatabaseCount('projects', 1);
    }

    /** @test */
    public function user_can_store_a_new_project_without_description_and_hourly_wage_filled(): void
    {
        $this->requestData['description'] = null;
        $this->requestData['hourly_wage'] = null;

        $this->post(route('projects.store'), $this->requestData)
            ->assertOk();

        $this->assertDatabaseHas('projects', $this->requestData);
        $this->assertDatabaseCount('projects', 1);
    }

    /** @test */
    public function user_can_update_a_project(): void
    {
        $project = Project::factory()->create();

        $this->put(route('projects.update', $project), $this->requestData)
            ->assertOk();

        $this->assertDatabaseHas('projects', $this->requestData + ['id' => $project->id]);
    }

    /** @test */
    public function user_can_update_a_project_without_description_and_hourly_wage_filled(): void
    {
        $project = Project::factory()->create();
        $this->requestData['description'] = null;
        $this->requestData['hourly_wage'] = null;

        $this->put(route('projects.update', $project), $this->requestData)
            ->assertOk();

        $this->assertDatabaseHas('projects', $this->requestData + ['id' => $project->id]);
    }

    /** @test */
    public function user_can_destroy_a_project(): void
    {
        $project = Project::factory()->create();

        // Soft delete
        $this->delete(route('projects.destroy', $project))
            ->assertOk();

        $this->assertSoftDeleted($project);

        // Force delete
        $this->delete(route('projects.destroy_permanent', $project))
            ->assertOk();

        $this->assertModelMissing($project);
    }

    /** @test */
    public function user_can_restore_a_soft_deleted_project(): void
    {
        $project = Project::factory()->create(['deleted_at' => now()]);

        $this->get(route('projects.restore', $project))
            ->assertOk();

        $this->assertNotSoftDeleted($project);
    }
}
