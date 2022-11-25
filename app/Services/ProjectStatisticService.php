<?php

namespace App\Services;

use App\Enums\WorkStatisticTypes;
use App\Models\Project;
use Illuminate\Support\Carbon;

class ProjectStatisticService
{
    public function calculateWorkTime(Project $project, WorkStatisticTypes $type): string
    {
        $dateUnit = $type->value;
        $isPersianDatetime = $project->use_persian_datetime_in_statistic;
        $totalDuration = 0;

        $times = ($type === WorkStatisticTypes::ALL)
            ? $project->times()
                ->whereNotNull('end_at')
                ->get()
            : $project->times()
                ->whereBetween('start_at', [
                    $this->startOfUnitDate($dateUnit, $isPersianDatetime)->subDay(),
                    $this->endOfUnitDate($dateUnit, $isPersianDatetime),
                ])
                ->whereBetween('end_at', [
                    $this->startOfUnitDate($dateUnit, $isPersianDatetime),
                    $this->endOfUnitDate($dateUnit, $isPersianDatetime)->addDay(),
                ])
                ->whereNotNull('end_at')
                ->get();

        foreach ($times as $time) {
            if ($type === WorkStatisticTypes::ALL) {
                $totalDuration += $time->end_at->diffInSeconds($time->start_at);
            } elseif (
                $this->startOfUnitDate($dateUnit, $isPersianDatetime)->toDateString() === $time->end_at->toDateString() &&
                $this->startOfUnitDate($dateUnit, $isPersianDatetime)->subDay()->toDateString() === $time->start_at->toDateString()
            ) {
                $totalDuration += $time->end_at->diffInSeconds($this->startOfUnitDate($dateUnit, $isPersianDatetime));
            } elseif (
                (
                    $this->startOfUnitDate($dateUnit, $isPersianDatetime)->toDateString() <= $time->start_at->toDateString() &&
                    $this->endOfUnitDate($dateUnit, $isPersianDatetime)->toDateString() >= $time->start_at->toDateString()
                ) && (
                    $this->startOfUnitDate($dateUnit, $isPersianDatetime)->toDateString() <= $time->end_at->toDateString() &&
                    $this->endOfUnitDate($dateUnit, $isPersianDatetime)->toDateString() >= $time->end_at->toDateString()
                )
            ) {
                $totalDuration += $time->end_at->diffInSeconds($time->start_at);
            } elseif (
                $this->endOfUnitDate($dateUnit, $isPersianDatetime)->toDateString() === $time->start_at->toDateString() &&
                $this->endOfUnitDate($dateUnit, $isPersianDatetime)->addDay()->toDateString() === $time->end_at->toDateString()
            ) {
                $totalDuration += $this->endOfUnitDate($dateUnit, $isPersianDatetime)->diffInSeconds($time->start_at) + 1;
            } elseif ($type === WorkStatisticTypes::TODAY) {
                $totalDuration += 86400; // 24 Hours in seconds for a day hours
            } elseif ($type === WorkStatisticTypes::WEEK) {
                $totalDuration += 86400 * 7; // 168 Hours in seconds for a week hours
            } elseif ($type === WorkStatisticTypes::MONTH) {
                $totalDuration += $this->endOfUnitDate('month', $isPersianDatetime)
                        ->diffInSeconds($this->startOfUnitDate('month', $isPersianDatetime)) + 1; // for a month hours
            }
        }

        return timestamp_to_hours_minutes($totalDuration);
    }

    public function calculateWorkWage(Project $project, WorkStatisticTypes $type): string
    {
        $time = $this->calculateWorkTime($project, $type);

        $minute = hours_minutes_to_minutes($time) / 60;
        $rate = (float) sprintf('%0.2f', $minute);
        $wage = sprintf('%0.2f', ($rate * $project->hourly_wage));

        return str_replace('.00', '', $wage);
    }

    public function getAllWorkTimes(Project $project): array
    {
        return [
            'today' => $this->calculateWorkTime($project, WorkStatisticTypes::TODAY),
            'week' => $this->calculateWorkTime($project, WorkStatisticTypes::WEEK),
            'month' => $this->calculateWorkTime($project, WorkStatisticTypes::MONTH),
            'all' => $this->calculateWorkTime($project, WorkStatisticTypes::ALL),
        ];
    }

    public function getAllWorkWages(Project $project): array
    {
        return [
            'today' => $this->calculateWorkWage($project, WorkStatisticTypes::TODAY),
            'week' => $this->calculateWorkWage($project, WorkStatisticTypes::WEEK),
            'month' => $this->calculateWorkWage($project, WorkStatisticTypes::MONTH),
            'all' => $this->calculateWorkWage($project, WorkStatisticTypes::ALL),
        ];
    }

    private function startOfUnitDate(string $unit, bool $isPersianDatetime = false): Carbon
    {
        $startUnit = 'start' . ucfirst($unit);

        return $isPersianDatetime && config('app.locale') === 'fa'
            ? today()->timestamp(verta()->{$startUnit}()->timestamp)
            : today()->startOf($unit);
    }

    private function endOfUnitDate(string $unit, bool $isPersianDatetime = false): Carbon
    {
        $endUnit = 'end' . ucfirst($unit);

        return $isPersianDatetime && config('app.locale') === 'fa'
            ? today()->timestamp(verta()->{$endUnit}()->timestamp)
            : today()->endOf($unit);
    }
}
