<?php

namespace Tests\Unit;

use App\Enums\WorkTimeStatisticTypes;
use App\Models\Project;
use App\Models\Time;
use App\Services\ProjectStatisticService;
use Tests\TestCase;

class TimeStatisticTest extends TestCase
{
    /* Calculating today work times scenarios
     * if today is 2022-10-10
     *
     * 1: start_at = 2022-10-09 22:00:00, end_at = 2022-10-10 01:00:00
     * Calculating difference time from today (2022-10-10 00:00:00) until end_at.
     *
     * 2: start_at = 2022-10-10 09:00:00, end_at = 2022-10-10 10:00:00
     * Calculating difference between time from start_at until end_at.
     *
     * 3: start_at = 2022-10-10 22:00:00, end_at = 2022-10-11 01:00:00
     * Calculating difference between time from start_at until end of today (2022-10-10 23:59:59).
     *
     * 4: start_at = 2022-10-09 10:00:00, end_at = 2022-10-11 10:00:00
     * Calculating today time equals to 24 hours
     *
     */
    /** @test */
    public function today_work_times(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $project = Project::factory()->create();
        $project->times()->create([
            'start_at' => today()->subDay()->hours(22),
            'end_at' => today()->hours(1)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(9),
            'end_at' => today()->hours(10)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(22),
            'end_at' => today()->addDay()->hours(1)
        ]);
        $project->times()->create([
            'start_at' => today()->subDay()->hours(10),
            'end_at' => today()->addDay()->hours(10)
        ]);

        // Act:
        $todayWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::TODAY);

        // Assert:
        $this->assertEquals('28:00', $todayWorkTime);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /* Calculating this week work times scenarios
     * if today is 2022-10-10
     * start of week is 2022-10-10
     * end of week is 2022-10-16
     *
     * 1: start_at = 2022-10-09 22:00:00, end_at = 2022-10-10 01:00:00
     * Calculating difference time from start of week (2022-10-10 00:00:00) until end_at.
     *
     * 2: start_at = 2022-10-10 09:00:00, end_at = 2022-10-10 10:00:00
     * Calculating difference between time from start_at until end_at.
     *
     * 3: start_at = 2022-10-16 22:00:00, end_at = 2022-10-17 01:00:00
     * Calculating difference between time from start_at until end of week (2022-10-16 23:59:59).
     *
     * 4: start_at = 2022-10-09 10:00:00, end_at = 2022-10-17 10:00:00
     * Calculating current week time equals to 168 hours
     *
     */
    /** @test */
    public function this_week_work_times(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $project = Project::factory()->create();
        $project->times()->create([
            'start_at' => today()->startOf('week')->subDay()->hours(22),
            'end_at' => today()->startOf('week')->hours(1)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(9),
            'end_at' => today()->hours(10)
        ]);
        $project->times()->create([
            'start_at' => today()->endOf('week')->setTimeFromTimeString('22:00'),
            'end_at' => today()->endOf('week')->addDay()->setTimeFromTimeString('01:00')
        ]);
        $project->times()->create([
            'start_at' => today()->startOf('week')->subDay()->hours(10),
            'end_at' => today()->endOf('week')->addDay()->setTimeFromTimeString('10:00')
        ]);

        // Act:
        $thisWeekWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::WEEK);

        // Assert:
        $this->assertEquals('172:00', $thisWeekWorkTime);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /* Calculating this month work times scenarios
     * if today is 2022-10-10
     * start of month is 2022-10-01
     * end of month is 2022-10-31
     *
     * 1: start_at = 2022-09-30 22:00:00, end_at = 2022-10-01 01:00:00
     * Calculating difference time from start of month (2022-10-01 00:00:00) until end_at.
     *
     * 2: start_at = 2022-10-10 09:00:00, end_at = 2022-10-10 10:00:00
     * Calculating difference between time from start_at until end_at.
     *
     * 3: start_at = 2022-10-31 22:00:00, end_at = 2022-11-01 01:00:00
     * Calculating difference between time from start_at until end of month (2022-10-31 23:59:59).
     *
     * 4: start_at = 2022-09-30 10:00:00, end_at = 2022-11-01 10:00:00
     * Calculating current month time equals to a month in hours
     *
     */
    /** @test */
    public function this_month_work_times(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $thisMonthInSeconds = today()->endOfMonth()->diffInSeconds(today()->startOfMonth()) + 1;
        $project = Project::factory()->create();
        $project->times()->create([
            'start_at' => today()->startOfMonth()->subDay()->hours(22),
            'end_at' => today()->startOfMonth()->hours(1)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(9),
            'end_at' => today()->hours(10)
        ]);
        $project->times()->create([
            'start_at' => today()->endOfMonth()->setTimeFromTimeString('22:00'),
            'end_at' => today()->endOfMonth()->addDay()->setTimeFromTimeString('01:00')
        ]);
        $project->times()->create([
            'start_at' => today()->startOfMonth()->subDay()->hours(10),
            'end_at' => today()->endOfMonth()->addDay()->setTimeFromTimeString('10:00')
        ]);

        // Act:
        $thisMonthWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::MONTH);

        // Assert:
        $this->assertEquals(sum_times($thisMonthInSeconds, '04:00'), $thisMonthWorkTime);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /** @test */
    public function all_work_times(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $project = Project::factory()->create();
        $project->times()->create(['start_at' => '2022-10-10 10:00:00', 'end_at' => '2022-10-10 12:00:00']);
        $project->times()->create(['start_at' => '2022-10-11 22:00:00', 'end_at' => '2022-10-12 02:00:00']);
        $project->times()->create(['start_at' => '2022-10-10 10:00:00', 'end_at' => '2022-10-12 12:00:00']);

        // Act:
        $allWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::ALL);

        // Assert:
        $this->assertEquals('56:00', $allWorkTime);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /** @test */
    public function when_the_end_at_is_null_it_should_not_be_calculated(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $project = Project::factory()->create();
        $project->times()->create([
            'start_at' => today()->hours(9),
            'end_at' => today()->hours(10)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(9),
            'end_at' => null
        ]);

        // Act:
        $todayWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::TODAY);
        $allWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::ALL);

        // Assert:
        $this->assertEquals('01:00', $todayWorkTime);
        $this->assertEquals('01:00', $allWorkTime);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /** @test */
    public function today_work_times_in_persian_datetime(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        /* @var \App\Models\Project $project */
        $project = Project::factory()->usePersianDatetimeForStatistic()->create();
        $project->times()->create([
            'start_at' => today()->subDay()->hours(22),
            'end_at' => today()->hours(1)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(9),
            'end_at' => today()->hours(10)
        ]);
        $project->times()->create([
            'start_at' => today()->hours(22),
            'end_at' => today()->addDay()->hours(1)
        ]);
        $project->times()->create([
            'start_at' => today()->subDay()->hours(10),
            'end_at' => today()->addDay()->hours(10)
        ]);

        // Act:
        $todayWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::TODAY);

        // Assert:
        $this->assertEquals('28:00', $todayWorkTime);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /** @test */
    public function this_week_work_times_in_persian_datetime(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        /* @var \App\Models\Project $project */
        $project = Project::factory()->usePersianDatetimeForStatistic()->create();
        $project->times()->create([
            'start_at' => verta_to_carbon(verta()->startWeek())->subDay()->hours(22),
            'end_at' => verta_to_carbon(verta()->startWeek())->hours(1)
        ]);
        $project->times()->create([
            'start_at' => verta_to_carbon(today()->toJalali())->hours(9),
            'end_at' => verta_to_carbon(today()->toJalali())->hours(10)
        ]);
        $project->times()->create([
            'start_at' => verta_to_carbon(verta()->endWeek())->setTimeFromTimeString('22:00'),
            'end_at' => verta_to_carbon(verta()->endWeek())->addDay()->setTimeFromTimeString('01:00')
        ]);
        $project->times()->create([
            'start_at' => verta_to_carbon(verta()->startWeek())->subDay()->hours(10),
            'end_at' => verta_to_carbon(verta()->endWeek())->addDay()->setTimeFromTimeString('10:00')
        ]);

        // Act:
        $thisWeekWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::WEEK);

        // Assert:
        $this->assertEquals('172:00', $thisWeekWorkTime);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /** @test */
    public function this_month_work_times_in_persian_datetime(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        $thisMonthInSeconds = today()->endOfMonth()->diffInSeconds(today()->startOfMonth()) + 1;

        /* @var \App\Models\Project $project */
        $project = Project::factory()->usePersianDatetimeForStatistic()->create();
        $project->times()->create([
            'start_at' => verta_to_carbon(verta()->startMonth())->subDay()->hours(22),
            'end_at' => verta_to_carbon(verta()->startMonth())->hours(1)
        ]);
        $project->times()->create([
            'start_at' => verta_to_carbon(today()->toJalali())->hours(9),
            'end_at' => verta_to_carbon(today()->toJalali())->hours(10)
        ]);
        $project->times()->create([
            'start_at' => verta_to_carbon(verta()->endMonth())->setTimeFromTimeString('22:00'),
            'end_at' => verta_to_carbon(verta()->endMonth())->addDay()->setTimeFromTimeString('01:00')
        ]);
        $project->times()->create([
            'start_at' => verta_to_carbon(verta()->startMonth())->subDay()->hours(10),
            'end_at' => verta_to_carbon(verta()->endMonth())->addDay()->setTimeFromTimeString('10:00')
        ]);

        // Act:
        $thisMonthWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::MONTH);

        // Assert:
        $this->assertEquals(sum_times($thisMonthInSeconds, '04:00'), $thisMonthWorkTime);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }

    /** @test */
    public function all_work_times_in_persian_datetime(): void
    {
        // Start Faking:
        Project::fake();
        Time::fake();

        // Arrange:
        /* @var \App\Models\Project $project */
        $project = Project::factory()->usePersianDatetimeForStatistic()->create();
        $project->times()->create(['start_at' => '2022-10-10 10:00:00', 'end_at' => '2022-10-10 12:00:00']);
        $project->times()->create(['start_at' => '2022-10-11 22:00:00', 'end_at' => '2022-10-12 02:00:00']);
        $project->times()->create(['start_at' => '2022-10-10 10:00:00', 'end_at' => '2022-10-12 12:00:00']);

        // Act:
        $allWorkTime = (new ProjectStatisticService())->calculateWorkTime($project, WorkTimeStatisticTypes::ALL);

        // Assert:
        $this->assertEquals('56:00', $allWorkTime);

        // Stop Faking:
        Project::stopFaking();
        Time::stopFaking();
    }
}
