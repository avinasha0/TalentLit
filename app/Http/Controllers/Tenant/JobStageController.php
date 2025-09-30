<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use App\Models\JobStage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JobStageController extends Controller
{
    public function index(string $tenant, JobOpening $job)
    {
        $stages = $job->jobStages()->orderBy('sort_order')->get();

        if (request()->expectsJson()) {
            return response()->json([
                'stages' => $stages->map(function ($stage) {
                    return [
                        'id' => $stage->id,
                        'name' => $stage->name,
                        'sort_order' => $stage->sort_order,
                        'is_terminal' => $stage->is_terminal,
                        'applications_count' => $stage->applications()->count(),
                    ];
                })
            ]);
        }

        return response()->json($stages);
    }

    public function store(Request $request, string $tenant, JobOpening $job)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_terminal' => 'boolean',
        ]);

        // Get the next sort order
        $maxOrder = $job->jobStages()->max('sort_order') ?? 0;
        
        $stage = $job->jobStages()->create([
            'tenant_id' => tenant_id(),
            'name' => $validated['name'],
            'sort_order' => $maxOrder + 1,
            'is_terminal' => $validated['is_terminal'] ?? false,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'stage' => [
                    'id' => $stage->id,
                    'name' => $stage->name,
                    'sort_order' => $stage->sort_order,
                    'is_terminal' => $stage->is_terminal,
                    'applications_count' => 0,
                ]
            ], 201);
        }

        return redirect()
            ->back()
            ->with('success', 'Stage created successfully.');
    }

    public function update(Request $request, string $tenant, JobOpening $job, JobStage $stage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_terminal' => 'boolean',
        ]);

        $stage->update([
            'name' => $validated['name'],
            'is_terminal' => $validated['is_terminal'] ?? false,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'stage' => [
                    'id' => $stage->id,
                    'name' => $stage->name,
                    'sort_order' => $stage->sort_order,
                    'is_terminal' => $stage->is_terminal,
                    'applications_count' => $stage->applications()->count(),
                ]
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Stage updated successfully.');
    }

    public function destroy(string $tenant, JobOpening $job, JobStage $stage)
    {
        // Check if stage has applications
        if ($stage->applications()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Cannot delete stage with applications. Please move applications to another stage first.'
                ], 422);
            }

            return redirect()
                ->back()
                ->with('error', 'Cannot delete stage with applications. Please move applications to another stage first.');
        }

        $stage->delete();

        // Reorder remaining stages
        $job->jobStages()
            ->where('sort_order', '>', $stage->sort_order)
            ->decrement('sort_order');

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Stage deleted successfully.']);
        }

        return redirect()
            ->back()
            ->with('success', 'Stage deleted successfully.');
    }

    public function reorder(Request $request, string $tenant, JobOpening $job)
    {
        $validated = $request->validate([
            'stages' => 'required|array',
            'stages.*' => 'required|uuid|exists:job_stages,id',
        ]);

        foreach ($validated['stages'] as $index => $stageId) {
            JobStage::where('id', $stageId)
                ->where('job_opening_id', $job->id)
                ->where('tenant_id', tenant_id())
                ->update(['sort_order' => $index + 1]);
        }

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Stages reordered successfully.']);
        }

        return redirect()
            ->back()
            ->with('success', 'Stages reordered successfully.');
    }
}
