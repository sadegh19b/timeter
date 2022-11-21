<?php

namespace App\Services;

use App\Enums\WorkTimeStatisticTypes;
use App\Models\Project;
use Illuminate\Support\Carbon;

class ProjectStatisticService
{
    public function calculateWorkTime(Project $project, WorkTimeStatisticTypes $type): string
    {
        $dateUnit = $type->value;
        $isPersianDatetime = $project->use_persian_datetime_in_statistic;
        $totalDuration = 0;

        $times = ($type === WorkTimeStatisticTypes::ALL)
            ? $project->times
            : $project->times()
                ->whereBetween('start_at', [
                    $this->startOfUnitDate($dateUnit, $isPersianDatetime)->subDay(),
                    $this->endOfUnitDate($dateUnit, $isPersianDatetime),
                ])
                ->whereBetween('end_at', [
                    $this->startOfUnitDate($dateUnit, $isPersianDatetime),
                    $this->endOfUnitDate($dateUnit, $isPersianDatetime)->addDay(),
                ])
                ->get();

        foreach ($times as $time) {
            if ($type === WorkTimeStatisticTypes::ALL) {
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
            } elseif ($type === WorkTimeStatisticTypes::TODAY) {
                $totalDuration += 86400; // 24 Hours in seconds for a day hours
            } elseif ($type === WorkTimeStatisticTypes::WEEK) {
                $totalDuration += 86400 * 7; // 168 Hours in seconds for a week hours
            } elseif ($type === WorkTimeStatisticTypes::MONTH) {
                $totalDuration += $this->endOfUnitDate('month', $isPersianDatetime)
                        ->diffInSeconds($this->startOfUnitDate('month', $isPersianDatetime)) + 1; // for a month hours
            }
        }

        return timestamp_to_hours_minutes($totalDuration);
    }

    private function startOfUnitDate(string $unit, bool $isPersianDatetime = false): Carbon
    {
        $startUnit = 'start' . ucfirst($unit);

        return $isPersianDatetime
            ? today()->timestamp(verta()->{$startUnit}()->timestamp)
            : today()->startOf($unit);
    }

    private function endOfUnitDate(string $unit, bool $isPersianDatetime = false): Carbon
    {
        $endUnit = 'end' . ucfirst($unit);

        return $isPersianDatetime
            ? today()->timestamp(verta()->{$endUnit}()->timestamp)
            : today()->endOf($unit);
    }
}
