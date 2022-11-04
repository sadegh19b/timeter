<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeRequest;
use App\Models\Project;
use App\Models\Time;

class TimeController extends Controller
{
    public function store(TimeRequest $request, Project $project): void
    {
        $project->times()->create($request->validated());
    }

    public function update(TimeRequest $request, Time $time): void
    {
        $time->update($request->validated());
    }

    public function destroy(Time $time): void
    {
        $time->delete();
    }

    public function restore($time_id): void
    {
        Time::withTrashed()->findOrFail($time_id)?->restore();
    }

    public function destroy_permanent($time_id): void
    {
        Time::withTrashed()->findOrFail($time_id)?->forceDelete();
    }
}
