<?php

namespace App\Enums;

enum WorkStatisticTypes: string
{
    case ALL = 'all';
    case TODAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
}
