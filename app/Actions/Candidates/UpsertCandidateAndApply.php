<?php

namespace App\Actions\Candidates;

use App\Mail\ApplicationReceived;
use App\Mail\NewApplication;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\Resume;
use App\Models\Tenant;
use App\Services\NotificationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpsertCandidateAndApply
{
    public function execute(
        Tenant $tenant,
        JobOpening $job,
        string $firstName,
        string $lastName,
        string $email,
        ?string $phone,
        ?UploadedFile $resume,
        bool $consent
    ): Application {
        return DB::transaction(function () use (
            $tenant,
            $job,
            $firstName,
            $lastName,
            $email,
            $phone,
            $resume

        ) {
            // Find or create candidate
            $candidate = Candidate::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'primary_email' => $email,
                ],
                [
                    'tenant_id' => $tenant->id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'primary_email' => $email,
                    'primary_phone' => $phone,
                    'source' => 'Career Site',
                ]
            );

            // Update candidate info if it was an existing candidate
            if ($candidate->wasRecentlyCreated === false) {
                $candidate->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'primary_phone' => $phone,
                ]);
            }

            // Handle resume upload
            $resumeModel = null;
            if ($resume) {
                $resumeModel = $this->storeResume($tenant, $candidate, $resume);
            }

            // Create or find application
            $application = Application::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'job_opening_id' => $job->id,
                    'candidate_id' => $candidate->id,
                ],
                [
                    'tenant_id' => $tenant->id,
                    'job_opening_id' => $job->id,
                    'candidate_id' => $candidate->id,
                    'status' => 'active',
                    'applied_at' => now(),
                ]
            );

            // Update application with resume if provided
            if ($resumeModel) {
                $application->update(['resume_id' => $resumeModel->id]);
            }

            // Send confirmation email (queued)
            $this->sendConfirmationEmail($candidate, $job, $application);

            // Send notification to recruiters (queued)
            $this->sendNewApplicationNotification($tenant, $job, $candidate, $application);

            return $application;
        });
    }

    private function storeResume(Tenant $tenant, Candidate $candidate, UploadedFile $resume): Resume
    {
        $extension = $resume->getClientOriginalExtension();
        $filename = Str::uuid().'.'.$extension;
        $path = "resumes/{$tenant->id}/{$filename}";

        // Store file
        Storage::disk('public')->put($path, file_get_contents($resume));

        // Create resume record
        return Resume::create([
            'tenant_id' => $tenant->id,
            'candidate_id' => $candidate->id,
            'disk' => 'public',
            'path' => $path,
            'filename' => $resume->getClientOriginalName(),
            'mime' => $resume->getMimeType(),
            'size' => $resume->getSize(),
        ]);
    }

    private function sendConfirmationEmail(Candidate $candidate, JobOpening $job, Application $application): void
    {
        try {
            if (config('mail.default') !== null) {
                Mail::to($candidate->primary_email)
                    ->queue(new ApplicationReceived($candidate, $job, $application));
            }
        } catch (\Exception $e) {
            // Log error but don't break the application flow
            \Log::info('Failed to send application confirmation email', [
                'candidate_id' => $candidate->id,
                'job_id' => $job->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendNewApplicationNotification(Tenant $tenant, JobOpening $job, Candidate $candidate, Application $application): void
    {
        try {
            if (config('mail.default') !== null) {
                $recruiters = NotificationService::getRecruiterUsers($tenant);
                
                foreach ($recruiters as $recruiter) {
                    Mail::to($recruiter->email)
                        ->queue(new NewApplication($tenant, $job, $candidate, $application));
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the application flow
            \Log::info('Failed to send new application notification', [
                'tenant_id' => $tenant->id,
                'job_id' => $job->id,
                'candidate_id' => $candidate->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
