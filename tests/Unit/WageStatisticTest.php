<?php

namespace Tests\Unit;

use App\Enums\WorkStatisticTypes;
use App\Models\Project;
use App\Models\Time;
use App\Services\ProjectStatisticService;
use Tests\TestCase;

class WageStatisticTest extends TestCase
{
    /** @test */
    public function the_project_today_wage(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $project = Project::factory()->create(['hourly_wage' => 1000]);
        $project->times()->create([
            'start_at' => today()->subDay()->hours(22),
            'end_at' => today()->hours(1)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(9),
            'end_at' => today()->hours(10)
        ]);

        // Act:
        $todayWorkWage = (new ProjectStatisticService())->calculateWorkWage($project, WorkStatisticTypes::TODAY);

        // Assert:
        $this->assertEquals('2000', $todayWorkWage);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /** @test */
    public function the_project_this_week_wage(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $project = Project::factory()->create(['hourly_wage' => 1000]);
        $project->times()->create([
            'start_at' => today()->startOf('week')->subDay()->hours(22),
            'end_at' => today()->startOf('week')->hours(1)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(9),
            'end_at' => today()->hours(10)
        ]);

        // Act:
        $weekWorkWage = (new ProjectStatisticService())->calculateWorkWage($project, WorkStatisticTypes::WEEK);

        // Assert:
        $this->assertEquals('2000', $weekWorkWage);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /** @test */
    public function the_project_this_month_wage(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $project = Project::factory()->create(['hourly_wage' => 1500]);
        $project->times()->create([
            'start_at' => today()->startOfMonth()->subDay()->hours(22),
            'end_at' => today()->startOfMonth()->hours(1)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(9),
            'end_at' => today()->hours(10)
        ]);

        // Act:
        $monthWorkWage = (new ProjectStatisticService())->calculateWorkWage($project, WorkStatisticTypes::MONTH);

        // Assert:
        $this->assertEquals('3000', $monthWorkWage);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /** @test */
    public function the_project_all_work_wages(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $project = Project::factory()->create(['hourly_wage' => 10.50]);
        $project->times()->create(['start_at' => '2022-10-10 10:00:00', 'end_at' => '2022-10-10 11:30:00']);
        $project->times()->create(['start_at' => '2022-10-11 22:00:00', 'end_at' => '2022-10-12 02:00:00']);

        // Act:
        $allWorkWages = (new ProjectStatisticService())->calculateWorkWage($project, WorkStatisticTypes::ALL);

        // Assert:
        $this->assertEquals('57.75', $allWorkWages);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }
}
