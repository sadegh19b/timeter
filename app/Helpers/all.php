<?php

if (!function_exists('persian_date')) {
    function persian_date(\Carbon\Carbon $date, string $format = 'Y/m/d - H:i:s'): string
    {
        return verta($date)
            ->setTimezone(new \Carbon\CarbonTimeZone('Asia/Tehran'))
            ->format($format);
    }
}
