<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ApplicationQuestion;
use App\Models\JobOpening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobQuestionsController extends Controller
{
    public function edit(string $tenant, JobOpening $job)
    {
        $tenantModel = tenant();
        
        // Ensure job belongs to tenant
        if ($job->tenant_id !== $tenantModel->id) {
            abort(404);
        }
        
        // Load job with its questions
        $job->load('applicationQuestions');
        
        // Get all available questions for this tenant
        $availableQuestions = ApplicationQuestion::where('tenant_id', $tenantModel->id)
            ->where('active', true)
            ->orderBy('label')
            ->get();
        
        return view('tenant.jobs.questions', [
            'tenant' => $tenantModel,
            'job' => $job,
            'availableQuestions' => $availableQuestions,
        ]);
    }

    public function update(Request $request, string $tenant, JobOpening $job)
    {
        $tenantModel = tenant();
        
        // Ensure job belongs to tenant
        if ($job->tenant_id !== $tenantModel->id) {
            abort(404);
        }
        
        $request->validate([
            'questions' => 'required|array',
            'questions.*.question_id' => 'required|exists:application_questions,id',
            'questions.*.required' => 'boolean',
        ]);
        
        DB::transaction(function () use ($request, $job, $tenantModel) {
            // Clear existing questions
            $job->applicationQuestions()->detach();
            
            // Add new questions with order and required status
            $questions = collect($request->questions)->mapWithKeys(function ($item, $index) {
                return [
                    $item['question_id'] => [
                        'sort_order' => $index + 1,
                        'required_override' => $item['required'] ?? null,
                    ]
                ];
            });
            
            $job->applicationQuestions()->sync($questions);
        });
        
        return redirect()->route('tenant.jobs.questions', [$tenant, $job])
            ->with('success', 'Job questions updated successfully.');
    }
}