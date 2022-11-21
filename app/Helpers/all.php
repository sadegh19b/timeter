<?php

if (!function_exists('persian_date')) {
    function persian_date(\Carbon\Carbon $date, string $format = 'Y/m/d - H:i:s'): string
    {
        return verta($date)
            ->setTimezone(new \Carbon\CarbonTimeZone('Asia/Tehran'))
            ->format($format);
    }
}

if (!function_exists('persian_to_georgian_datetime')) {
    function persian_to_georgian_datetime(string $persian_date, string $format = 'Y-m-d H:i:s'): string
    {
        return Verta::parse($persian_date)
            ->datetime()
            ->setTimezone(new \Carbon\CarbonTimeZone('Asia/Tehran'))
            ->format($format);
    }
}

if (!function_exists('verta_to_carbon')) {
    function verta_to_carbon(\Hekmatinasser\Verta\Verta $verta): \Illuminate\Support\Carbon
    {
        return now()->timestamp($verta->timestamp);
    }
}

if (!function_exists('timestamp_to_hours_minutes')) {
    function timestamp_to_hours_minutes(int $timestamp): string
    {
        $hours = floor($timestamp / 3600);
        $minutes = floor(($timestamp % 3600) / 60);

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}

if (!function_exists('hours_minutes_to_timestamp')) {
    function hours_minutes_to_timestamp(string $time): int
    {
        $_time = explode(':', $time);

        $hours = floor($_time[0] * 3600);
        $minutes = floor(($_time[1] % 3600) * 60);

        return (int) $hours + $minutes;
    }
}

if (!function_exists('sum_times')) {
    function sum_times(string $timeA, string $timeB): string
    {
        if (is_numeric($timeA) && count(explode(':', $timeA)) !== 2) {
            $timeA = timestamp_to_hours_minutes($timeA);
        }

        if (is_numeric($timeB) && count(explode(':', $timeB)) !== 2) {
            $timeB = timestamp_to_hours_minutes($timeB);
        }

        return timestamp_to_hours_minutes(
            hours_minutes_to_timestamp($timeA) + hours_minutes_to_timestamp($timeB)
        );
    }
}
