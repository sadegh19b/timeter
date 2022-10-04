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

        $this->requestData = Project::factory()->make()->toArray();
    }

    public function test_user_can_store_a_new_project(): void
    {
        $this->post(route('projects.store'), $this->requestData)
            ->assertOk();

        $this->assertDatabaseHas('projects', $this->requestData);
        $this->assertDatabaseCount('projects', 1);
    }

    public function test_user_can_update_a_project(): void
    {
        $project = Project::factory()->create();

        $this->put(route('projects.update', $project), $this->requestData)
            ->assertOk();

        $this->assertDatabaseHas('projects', $this->requestData + ['id' => $project->id]);
    }

    public function test_user_can_destroy_a_project(): void
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

    public function test_user_can_restore_a_soft_deleted_project(): void
    {
        $project = Project::factory()->create(['deleted_at' => now()]);

        $this->get(route('projects.restore', $project))
            ->assertOk();

        $this->assertNotSoftDeleted($project);
    }
}
