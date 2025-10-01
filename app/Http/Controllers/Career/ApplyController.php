<?php

namespace App\Http\Controllers\Career;

use App\Actions\Candidates\UpsertCandidateAndApply;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyRequest;
use App\Models\JobOpening;

class ApplyController extends Controller
{
    public function create(string $tenant, JobOpening $job)
    {
        $tenantModel = tenant();
        
        // Ensure job belongs to the current tenant
        if ($job->tenant_id !== $tenantModel->id) {
            abort(404);
        }

        // Ensure job is published
        if ($job->status !== 'published') {
            abort(404);
        }

        $branding = $tenantModel->branding;
        $job->load('applicationQuestions');

        return view('careers.apply', compact('job', 'tenant', 'branding'));
    }

    public function store(ApplyRequest $request, string $tenant, JobOpening $job, UpsertCandidateAndApply $upsertAction)
    {
        $tenantModel = tenant();
        
        // Ensure job belongs to the current tenant
        if ($job->tenant_id !== $tenantModel->id) {
            abort(404);
        }

        // Ensure job is published
        if ($job->status !== 'published') {
            abort(404);
        }

        // Load job with questions for validation
        $job->load('applicationQuestions');

        // Validate custom questions
        $customAnswers = [];
        $validationRules = [];
        $validationMessages = [];

        foreach ($job->applicationQuestions as $question) {
            $isRequired = $question->pivot->required_override ?? $question->required;
            $fieldName = "question_{$question->id}";

            if ($isRequired) {
                $validationRules[$fieldName] = 'required';
                $validationMessages["{$fieldName}.required"] = "The {$question->label} field is required.";
            }

            // Add type-specific validation
            switch ($question->type) {
                case 'email':
                    $validationRules[$fieldName] = ($validationRules[$fieldName] ?? '') . '|email';
                    break;
                case 'file':
                    $validationRules[$fieldName] = ($validationRules[$fieldName] ?? '') . '|file|max:5120';
                    break;
            }

            // Collect answers
            if ($request->has($fieldName)) {
                $customAnswers[$question->id] = $request->input($fieldName);
            }
        }

        // Validate custom questions
        $request->validate($validationRules, $validationMessages);

        try {
            $application = $upsertAction->execute(
                tenant: $tenantModel,
                job: $job,
                firstName: $request->first_name,
                lastName: $request->last_name,
                email: $request->email,
                phone: $request->phone,
                resume: $request->file('resume'),
                consent: $request->boolean('consent'),
                customAnswers: $customAnswers
            );

            return redirect()->route('careers.success', [
                'tenant' => $tenantModel->slug,
                'job' => $job->slug,
            ])->with('application_id', $application->id);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'There was an error processing your application. Please try again.']);
        }
    }

    public function success(string $tenant, JobOpening $job)
    {
        // Ensure job belongs to the current tenant
        if ($job->tenant_id !== tenant()->id) {
            abort(404);
        }

        return view('careers.success', compact('job'));
    }
}
