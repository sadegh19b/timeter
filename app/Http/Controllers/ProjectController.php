<?php

namespace App\Http\Controllers;

use App\Enums\WorkStatisticTypes;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Services\ProjectStatisticService;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(ProjectStatisticService $statisticService): \Inertia\Response
    {
        $projects = Project::get()->map(function ($item) use ($statisticService) {
            $item->work_time = $statisticService->getAllWorkTimes($item);
            $item->work_wage = array_map(fn($item) => clean_number_format($item), $statisticService->getAllWorkWages($item));
            $item->active_time = $item->times()
                ->latest()
                ->whereNull('end_at')
                ->take(1)
                ->get(['id', 'start_at'])
                ->each(fn ($row) => $row->start_at_formatted = $row->start_at->format('Y-m-d H:i'))
                ->first();

            return $item;
        });

        return Inertia::render('index', compact('projects'));
    }

    public function store(ProjectRequest $request): void
    {
        Project::create($request->validated());
    }

    public function show($id)
    {
    }

    public function update(ProjectRequest $request, Project $project): void
    {
        $project->update($request->validated());
    }

    public function destroy(Project $project): void
    {
        $project->delete();
    }

    public function restore($project_id): void
    {
        Project::withTrashed()->findOrFail($project_id)?->restore();
    }

    public function destroy_permanent($project_id): void
    {
        Project::withTrashed()->findOrFail($project_id)?->forceDelete();
    }
}
