<?php

namespace App\Http\Controllers\Career;

use App\Actions\Candidates\UpsertCandidateAndApply;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyRequest;
use App\Mail\CareerApplyOtpMail;
use App\Models\CareerApplyEmailOtp;
use App\Models\JobOpening;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApplyController extends Controller
{
    public function create(Request $request)
    {
        [$tenantModel, $job] = $this->resolvePublishedCareersJob($request);

        if (! $tenantModel->careers_enabled) {
            $branding = $tenantModel->branding;

            return view('careers.disabled', ['tenant' => $tenantModel, 'branding' => $branding]);
        }

        $branding = $tenantModel->branding;
        $job->load('applicationQuestions');

        $isSubdomain = $request->routeIs('subdomain.careers.apply.create');
        $sendApplyEmailOtpUrl = $isSubdomain
            ? route('subdomain.careers.apply.email-otp.send', ['job' => $job->slug])
            : route('careers.apply.email-otp.send', ['tenant' => $tenantModel->slug, 'job' => $job->slug]);
        $verifyApplyEmailOtpUrl = $isSubdomain
            ? route('subdomain.careers.apply.email-otp.verify', ['job' => $job->slug])
            : route('careers.apply.email-otp.verify', ['tenant' => $tenantModel->slug, 'job' => $job->slug]);

        $applyEmailInitiallyVerified = CareerApplyEmailOtp::sessionMatches(
            $tenantModel,
            $job,
            CareerApplyEmailOtp::normalizeEmail((string) old('email', ''))
        );

        return view('careers.apply', compact(
            'job',
            'tenantModel',
            'branding',
            'sendApplyEmailOtpUrl',
            'verifyApplyEmailOtpUrl',
            'applyEmailInitiallyVerified',
        ));
    }

    public function sendApplyEmailOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        [$tenantModel, $job] = $this->resolvePublishedCareersJob($request);

        if (! $tenantModel->careers_enabled) {
            return response()->json(['ok' => false, 'message' => 'Careers are not available.'], 404);
        }
        $email = CareerApplyEmailOtp::normalizeEmail($validated['email']);

        if (! CareerApplyEmailOtp::canResend($tenantModel, $job, $email)) {
            return response()->json([
                'ok' => false,
                'message' => 'Please wait before requesting another code.',
                'retry_after_seconds' => CareerApplyEmailOtp::remainingResendCooldownSeconds($tenantModel, $job, $email),
            ], 429);
        }

        try {
            $record = CareerApplyEmailOtp::issue($tenantModel, $job, $email);
            Mail::to($email)->send(new CareerApplyOtpMail($tenantModel, $job, $email, $record->otp));
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'message' => 'Could not send email. Please try again later.',
            ], 500);
        }

        CareerApplyEmailOtp::forgetSession();

        return response()->json([
            'ok' => true,
            'message' => 'We sent a 6-digit code to your email.',
        ]);
    }

    public function verifyApplyEmailOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        [$tenantModel, $job] = $this->resolvePublishedCareersJob($request);

        if (! $tenantModel->careers_enabled) {
            return response()->json(['ok' => false, 'message' => 'Careers are not available.'], 404);
        }
        $email = CareerApplyEmailOtp::normalizeEmail($validated['email']);

        if (! CareerApplyEmailOtp::verifyAndConsume($tenantModel, $job, $email, $validated['otp'])) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid or expired code. Try again or request a new code.',
            ], 422);
        }

        CareerApplyEmailOtp::putVerifiedSession($tenantModel, $job, $email);

        return response()->json([
            'ok' => true,
            'message' => 'Email verified. You can submit your application.',
        ]);
    }

    public function store(ApplyRequest $request, UpsertCandidateAndApply $upsertAction)
    {
        [$tenantModel, $job] = $this->resolvePublishedCareersJob($request);

        if (! $tenantModel->careers_enabled) {
            $branding = $tenantModel->branding;

            return view('careers.disabled', ['tenant' => $tenantModel, 'branding' => $branding]);
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
                    $validationRules[$fieldName] = ($validationRules[$fieldName] ?? '').'|email';
                    break;
                case 'file':
                    $validationRules[$fieldName] = ($validationRules[$fieldName] ?? '').'|file|max:5120';
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
            $submission = $upsertAction->execute(
                tenant: $tenantModel,
                job: $job,
                firstName: $request->first_name,
                lastName: $request->last_name,
                email: $request->email,
                phone: $request->phone,
                currentCtc: $request->current_ctc,
                expectedCtc: $request->expected_ctc,
                resume: $request->file('resume'),
                consent: $request->boolean('consent'),
                customAnswers: $customAnswers
            );

            CareerApplyEmailOtp::forgetSession();
            CareerApplyEmailOtp::invalidateForApplication(
                $tenantModel,
                $job,
                CareerApplyEmailOtp::normalizeEmail((string) $request->input('email', ''))
            );

            if ($request->routeIs('subdomain.careers.apply.store')) {
                return redirect()->route('subdomain.careers.success', ['job' => $job->slug])
                    ->with('application_id', $submission->application->id)
                    ->with('applicant_portal_show_modal', $submission->applicantPortal->canAccessApplicantPortal)
                    ->with('applicant_portal_new_account', $submission->applicantPortal->credentialsEmailQueued);
            }

            return redirect()->route('careers.success', [
                'tenant' => $tenantModel->slug,
                'job' => $job->slug,
            ])
                ->with('application_id', $submission->application->id)
                ->with('applicant_portal_show_modal', $submission->applicantPortal->canAccessApplicantPortal)
                ->with('applicant_portal_new_account', $submission->applicantPortal->credentialsEmailQueued);

        } catch (\Exception $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors(['error' => 'There was an error processing your application. Please try again.']);
        }
    }

    public function success(Request $request)
    {
        [$tenantModel, $job] = $this->resolvePublishedCareersJob($request);

        if (! $tenantModel->careers_enabled) {
            $branding = $tenantModel->branding;

            return view('careers.disabled', ['tenant' => $tenantModel, 'branding' => $branding]);
        }

        return view('careers.success', compact('job'));
    }

    /**
     * Store intended applicant portal URL then send the user to global login.
     */
    public function applicantLoginRedirect(?string $tenant = null)
    {
        $tenantModel = tenant();
        if (! $tenantModel && $tenant) {
            $tenantModel = Tenant::where('slug', $tenant)->first();
        }
        if (! $tenantModel) {
            abort(404, 'Tenant not found');
        }

        if (Auth::guard('candidate')->check()) {
            return redirect()->to($tenantModel->getApplicantPortalUrl());
        }

        if (Auth::check()) {
            return redirect()->route('tenant.dashboard', $tenantModel->slug);
        }

        session(['url.intended' => $tenantModel->getApplicantPortalUrl()]);

        if ($tenantModel->usesEnterpriseSubdomain()) {
            return redirect()->route('subdomain.candidate.login');
        }

        return redirect()->route('candidate.login', ['tenant' => $tenantModel->slug]);
    }

    /**
     * @return array{0: Tenant, 1: JobOpening}
     */
    protected function resolvePublishedCareersJob(Request $request): array
    {
        $tenantModel = tenant();
        if (! $tenantModel && $request->route('tenant')) {
            $tenantModel = Tenant::where('slug', $request->route('tenant'))->first();
        }

        if (! $tenantModel) {
            abort(404, 'Tenant not found');
        }

        $jobSlug = $request->route('job');
        if (! $jobSlug) {
            abort(404, 'Job not found');
        }

        $job = JobOpening::where('slug', $jobSlug)
            ->where('tenant_id', $tenantModel->id)
            ->where('status', 'published')
            ->first();

        if (! $job) {
            abort(404, 'Job not found');
        }

        return [$tenantModel, $job];
    }
}
