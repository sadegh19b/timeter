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
        'pay_per_hour'
    ];

    public function times(): HasMany
    {
        return $this->hasMany(Time::class);
    }
}
