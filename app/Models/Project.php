<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Imanghafoori\EloquentMockery\MockableModel;

class Project extends Model
{
    use HasFactory, MockableModel, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'hourly_wage',
        'use_persian_datetime_in_statistic'
    ];

    protected $casts = [
        'use_persian_datetime_in_statistic' => 'boolean'
    ];

    public function times(): HasMany
    {
        return $this->hasMany(Time::class);
    }
}
