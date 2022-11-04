<?php

namespace App\Http\Controllers;

use App\Enums\WorkTimeStatisticTypes;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Services\ProjectStatisticService;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(): \Inertia\Response
    {
        $statisticService = new ProjectStatisticService();
        $projects = Project::get()->map(function ($item) use ($statisticService) {
            $item->today_work_time = $statisticService->calculateWorkTime($item, WorkTimeStatisticTypes::TODAY);
            $item->week_work_time  = $statisticService->calculateWorkTime($item, WorkTimeStatisticTypes::WEEK);
            $item->month_work_time = $statisticService->calculateWorkTime($item, WorkTimeStatisticTypes::MONTH);
            $item->all_work_time   = $statisticService->calculateWorkTime($item, WorkTimeStatisticTypes::ALL);

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
