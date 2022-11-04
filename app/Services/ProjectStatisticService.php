<?php

namespace App\Services;

use App\Enums\WorkTimeStatisticTypes;
use App\Models\Project;

class ProjectStatisticService
{
    public function calculateWorkTime(Project $project, WorkTimeStatisticTypes $type): string
    {
        $dateUnit = $type->value;
        $totalDuration = 0;

        $times = ($type === WorkTimeStatisticTypes::ALL)
            ? $project->times
            : $project->times()
                ->whereBetween('start_at', [today()->startOf($dateUnit)->subDay(), today()->endOf($dateUnit)])
                ->whereBetween('end_at', [today()->startOf($dateUnit), today()->endOf($dateUnit)->addDay()])
                ->get();

        foreach ($times as $time) {
            if ($type === WorkTimeStatisticTypes::ALL) {
                $totalDuration += $time->end_at->diffInSeconds($time->start_at);
            } elseif (
                today()->startOf($dateUnit)->toDateString() === $time->end_at->toDateString() &&
                today()->startOf($dateUnit)->subDay()->toDateString() === $time->start_at->toDateString()
            ) {
                $totalDuration += $time->end_at->diffInSeconds(today()->startOf($dateUnit));
            } elseif (
                (
                    today()->startOf($dateUnit)->toDateString() <= $time->start_at->toDateString() &&
                    today()->endOf($dateUnit)->toDateString() >= $time->start_at->toDateString()
                ) && (
                    today()->startOf($dateUnit)->toDateString() <= $time->end_at->toDateString() &&
                    today()->endOf($dateUnit)->toDateString() >= $time->end_at->toDateString()
                )
            ) {
                $totalDuration += $time->end_at->diffInSeconds($time->start_at);
            } elseif (
                today()->endOf($dateUnit)->toDateString() === $time->start_at->toDateString() &&
                today()->endOf($dateUnit)->addDay()->toDateString() === $time->end_at->toDateString()
            ) {
                $totalDuration += today()->endOf($dateUnit)->diffInSeconds($time->start_at) + 1;
            } elseif ($type === WorkTimeStatisticTypes::TODAY) {
                $totalDuration += 86400; // 24 Hours in seconds for a day hours
            } elseif ($type === WorkTimeStatisticTypes::WEEK) {
                $totalDuration += 86400 * 7; // 168 Hours in seconds for a week hours
            } elseif ($type === WorkTimeStatisticTypes::MONTH) {
                $totalDuration += today()->endOfMonth()->diffInSeconds(today()->startOfMonth()) + 1; // for a month hours
            }
        }

        return timestamp_to_hours_minutes($totalDuration);
    }
}
