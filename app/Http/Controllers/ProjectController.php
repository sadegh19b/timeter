<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(): \Inertia\Response
    {
        $projects = Project::get();

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

    public function restore($id): void
    {
        Project::withTrashed()->findOrFail($id)?->restore();
    }

    public function destroy_permanent($id): void
    {
        Project::withTrashed()->findOrFail($id)?->forceDelete();
    }
}
