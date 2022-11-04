<?php

namespace App\Enums;

enum WorkTimeStatisticTypes: string
{
    case ALL = 'all';
    case TODAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
}
