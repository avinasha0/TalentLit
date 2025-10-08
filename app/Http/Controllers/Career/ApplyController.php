<?php

namespace App\Http\Controllers\Career;

use App\Actions\Candidates\UpsertCandidateAndApply;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyRequest;
use App\Models\JobOpening;
use Illuminate\Support\Facades\Storage;

class ApplyController extends Controller
{
    public function create(string $tenant, string $jobSlug)
    {
        $tenantModel = tenant();
        
        // Fallback: get tenant from route parameter if tenant() returns null
        if (!$tenantModel) {
            $tenantModel = \App\Models\Tenant::where('slug', $tenant)->first();
        }
        
        if (!$tenantModel) {
            abort(404, 'Tenant not found');
        }
        
        // Check if careers page is enabled
        if (!$tenantModel->careers_enabled) {
            $branding = $tenantModel->branding;
            return view('careers.disabled', ['tenant' => $tenantModel, 'branding' => $branding]);
        }
        
        // Find job by slug within the current tenant
        $job = \App\Models\JobOpening::where('slug', $jobSlug)
            ->where('tenant_id', $tenantModel->id)
            ->where('status', 'published')
            ->first();
            
        if (!$job) {
            abort(404, 'Job not found');
        }

        // Ensure job is published
        if ($job->status !== 'published') {
            abort(404);
        }

        $branding = $tenantModel->branding;
        $job->load('applicationQuestions');

        return view('careers.apply', compact('job', 'tenantModel', 'branding'));
    }

    public function store(ApplyRequest $request, string $tenant, string $jobSlug, UpsertCandidateAndApply $upsertAction)
    {
        $tenantModel = tenant();
        
        // Fallback: get tenant from route parameter if tenant() returns null
        if (!$tenantModel) {
            $tenantModel = \App\Models\Tenant::where('slug', $tenant)->first();
        }
        
        if (!$tenantModel) {
            abort(404, 'Tenant not found');
        }
        
        // Check if careers page is enabled
        if (!$tenantModel->careers_enabled) {
            $branding = $tenantModel->branding;
            return view('careers.disabled', ['tenant' => $tenantModel, 'branding' => $branding]);
        }
        
        // Find job by slug within the current tenant
        $job = \App\Models\JobOpening::where('slug', $jobSlug)
            ->where('tenant_id', $tenantModel->id)
            ->where('status', 'published')
            ->first();
            
        if (!$job) {
            abort(404, 'Job not found');
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

    public function success(string $tenant, string $jobSlug)
    {
        $tenantModel = tenant();
        
        // Fallback: get tenant from route parameter if tenant() returns null
        if (!$tenantModel) {
            $tenantModel = \App\Models\Tenant::where('slug', $tenant)->first();
        }
        
        if (!$tenantModel) {
            abort(404, 'Tenant not found');
        }
        
        // Check if careers page is enabled
        if (!$tenantModel->careers_enabled) {
            $branding = $tenantModel->branding;
            return view('careers.disabled', ['tenant' => $tenantModel, 'branding' => $branding]);
        }
        
        // Find job by slug within the current tenant
        $job = \App\Models\JobOpening::where('slug', $jobSlug)
            ->where('tenant_id', $tenantModel->id)
            ->where('status', 'published')
            ->first();
            
        if (!$job) {
            abort(404, 'Job not found');
        }

        return view('careers.success', compact('job'));
    }
}
