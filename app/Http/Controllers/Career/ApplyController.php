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
        // Ensure job belongs to the current tenant
        if ($job->tenant_id !== tenant()->id) {
            abort(404);
        }

        // Ensure job is published
        if ($job->status !== 'published') {
            abort(404);
        }

        return view('careers.apply', compact('job'));
    }

    public function store(ApplyRequest $request, string $tenant, JobOpening $job, UpsertCandidateAndApply $upsertAction)
    {
        // Ensure job belongs to the current tenant
        if ($job->tenant_id !== tenant()->id) {
            abort(404);
        }

        // Ensure job is published
        if ($job->status !== 'published') {
            abort(404);
        }

        try {
            $application = $upsertAction->execute(
                tenant: tenant(),
                job: $job,
                firstName: $request->first_name,
                lastName: $request->last_name,
                email: $request->email,
                phone: $request->phone,
                resume: $request->file('resume'),
                consent: $request->boolean('consent')
            );

            return redirect()->route('careers.success', [
                'tenant' => tenant()->slug,
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
