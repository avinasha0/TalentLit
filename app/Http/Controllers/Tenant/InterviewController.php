<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Mail\InterviewCanceled;
use App\Mail\InterviewScheduled;
use App\Mail\InterviewUpdated;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\JobOpening;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InterviewController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request, string $tenant)
    {
        // Get the tenant model from the middleware
        $tenantModel = tenant();
        
        $interviews = Interview::with(['candidate', 'job', 'panelists', 'createdBy'])
            ->upcoming()
            ->orderBy('scheduled_at')
            ->paginate(20);

        // Apply filters
        if ($request->filled('candidate_id')) {
            $interviews->where('candidate_id', $request->candidate_id);
        }

        if ($request->filled('job_id')) {
            $interviews->where('job_id', $request->job_id);
        }

        if ($request->filled('status')) {
            $interviews->where('status', $request->status);
        }

        if ($request->filled('mode')) {
            $interviews->where('mode', $request->mode);
        }

        // Get filter options
        $candidates = Candidate::select('id', 'first_name', 'last_name', 'primary_email')->get();
        $jobs = JobOpening::select('id', 'title')->get();
        
        // Get users for panelist selection
        $users = User::whereHas('tenants', function ($query) use ($tenantModel) {
            $query->where('tenant_id', $tenantModel->id);
        })->select('id', 'name')->get();

        return view('tenant.interviews.index', compact('interviews', 'candidates', 'jobs', 'users', 'tenantModel'));
    }

    public function create(Request $request, string $tenant, Candidate $candidate)
    {
        $this->authorize('create', Interview::class);

        // Get jobs for this candidate's applications
        $jobs = JobOpening::whereHas('applications', function ($query) use ($candidate) {
            $query->where('candidate_id', $candidate->id);
        })->get();

        // Get all users in the tenant for panelist selection
        $users = User::whereHas('tenants', function ($query) {
            $query->where('tenant_id', tenant_id());
        })->get();

        return view('tenant.interviews.create', compact('candidate', 'jobs', 'users'));
    }

    public function store(Request $request, string $tenant, Candidate $candidate)
    {
        $this->authorize('create', Interview::class);

        $request->validate([
            'job_id' => 'nullable|exists:job_openings,id',
            'application_id' => 'nullable|exists:applications,id',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'mode' => 'required|in:onsite,remote,phone',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:2000',
            'panelists' => 'array',
            'panelists.*' => 'exists:users,id',
        ]);

        // Find application if job_id is provided
        $applicationId = null;
        if ($request->job_id) {
            $application = $candidate->applications()
                ->where('job_opening_id', $request->job_id)
                ->first();
            $applicationId = $application?->id;
        }

        $interview = Interview::create([
            'tenant_id' => tenant_id(),
            'candidate_id' => $candidate->id,
            'job_id' => $request->job_id,
            'application_id' => $applicationId,
            'scheduled_at' => $request->scheduled_at,
            'duration_minutes' => $request->duration_minutes,
            'mode' => $request->mode,
            'location' => $request->location,
            'meeting_link' => $request->meeting_link,
            'created_by' => Auth::id(),
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        // Attach panelists
        if ($request->has('panelists')) {
            $interview->panelists()->attach($request->panelists);
        }

        // Send notifications
        $this->sendInterviewNotifications($interview, 'scheduled');
        
        Log::info('Interview scheduled', [
            'interview_id' => $interview->id,
            'candidate_id' => $candidate->id,
            'scheduled_at' => $interview->scheduled_at,
            'panelists_count' => $interview->panelists()->count()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Interview scheduled successfully',
                'interview' => $interview->load(['candidate', 'job', 'panelists'])
            ]);
        }

        return redirect()
            ->route('tenant.candidates.show', ['tenant' => $request->route('tenant'), 'candidate' => $candidate])
            ->with('success', 'Interview scheduled successfully');
    }

    public function show(Interview $interview)
    {
        $this->authorize('view', $interview);

        $interview->load(['candidate', 'job', 'application', 'panelists', 'createdBy']);

        return view('tenant.interviews.show', compact('interview'));
    }

    public function edit(Interview $interview)
    {
        $this->authorize('update', $interview);

        $interview->load(['candidate', 'job', 'panelists']);

        // Get jobs for this candidate's applications
        $jobs = JobOpening::whereHas('applications', function ($query) use ($interview) {
            $query->where('candidate_id', $interview->candidate_id);
        })->get();

        // Get all users in the tenant for panelist selection
        $users = User::whereHas('tenants', function ($query) {
            $query->where('tenant_id', tenant_id());
        })->get();

        return view('tenant.interviews.edit', compact('interview', 'jobs', 'users'));
    }

    public function update(Request $request, Interview $interview)
    {
        $this->authorize('update', $interview);

        $request->validate([
            'job_id' => 'nullable|exists:job_openings,id',
            'application_id' => 'nullable|exists:applications,id',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'mode' => 'required|in:onsite,remote,phone',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:2000',
            'panelists' => 'array',
            'panelists.*' => 'exists:users,id',
        ]);

        $interview->update([
            'job_id' => $request->job_id,
            'application_id' => $request->application_id,
            'scheduled_at' => $request->scheduled_at,
            'duration_minutes' => $request->duration_minutes,
            'mode' => $request->mode,
            'location' => $request->location,
            'meeting_link' => $request->meeting_link,
            'notes' => $request->notes,
        ]);

        // Update panelists
        if ($request->has('panelists')) {
            $interview->panelists()->sync($request->panelists);
        }

        // Send update notifications
        $this->sendInterviewNotifications($interview, 'updated');
        
        Log::info('Interview updated', [
            'interview_id' => $interview->id,
            'candidate_id' => $interview->candidate_id,
            'scheduled_at' => $interview->scheduled_at
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Interview updated successfully',
                'interview' => $interview->load(['candidate', 'job', 'panelists'])
            ]);
        }

        return redirect()
            ->route('tenant.interviews.show', ['tenant' => $request->route('tenant'), 'interview' => $interview])
            ->with('success', 'Interview updated successfully');
    }

    public function cancel(Request $request, string $tenant, Interview $interview)
    {
        $this->authorize('update', $interview);

        $interview->update(['status' => 'canceled']);

        // Send cancellation notifications
        $this->sendInterviewNotifications($interview, 'canceled');
        
        Log::info('Interview canceled', [
            'interview_id' => $interview->id,
            'candidate_id' => $interview->candidate_id
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Interview canceled successfully'
            ]);
        }

        return redirect()
            ->route('tenant.interviews.index', ['tenant' => $request->route('tenant')])
            ->with('success', 'Interview canceled successfully');
    }

    public function complete(Request $request, string $tenant, Interview $interview)
    {
        $this->authorize('update', $interview);

        $interview->update(['status' => 'completed']);

        Log::info('Interview completed', [
            'interview_id' => $interview->id,
            'candidate_id' => $interview->candidate_id
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Interview marked as completed'
            ]);
        }

        return redirect()
            ->route('tenant.interviews.show', ['tenant' => $request->route('tenant'), 'interview' => $interview])
            ->with('success', 'Interview marked as completed');
    }

    public function destroy(Interview $interview)
    {
        $this->authorize('delete', $interview);

        $interview->delete();

        Log::info('Interview deleted', [
            'interview_id' => $interview->id,
            'candidate_id' => $interview->candidate_id
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Interview deleted successfully'
            ]);
        }

        return redirect()
            ->route('tenant.interviews.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Interview deleted successfully');
    }

    /**
     * Send interview notifications to candidate and panelists
     */
    private function sendInterviewNotifications(Interview $interview, string $type): void
    {
        try {
            // Load relationships
            $interview->load(['candidate', 'job', 'panelists']);

            // Send email to candidate
            if ($interview->candidate->primary_email) {
                $mailable = match($type) {
                    'scheduled' => new InterviewScheduled($interview),
                    'updated' => new InterviewUpdated($interview),
                    'canceled' => new InterviewCanceled($interview),
                    default => null
                };

                if ($mailable) {
                    Mail::to($interview->candidate->primary_email)->send($mailable);
                }
            }

            // Send email to panelists
            foreach ($interview->panelists as $panelist) {
                if ($panelist->email) {
                    $mailable = match($type) {
                        'scheduled' => new InterviewScheduled($interview),
                        'updated' => new InterviewUpdated($interview),
                        'canceled' => new InterviewCanceled($interview),
                        default => null
                    };

                    if ($mailable) {
                        Mail::to($panelist->email)->send($mailable);
                    }
                }
            }

            Log::info('Interview notifications sent', [
                'interview_id' => $interview->id,
                'type' => $type,
                'candidate_email' => $interview->candidate->primary_email,
                'panelists_count' => $interview->panelists->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send interview notifications', [
                'interview_id' => $interview->id,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a directly scheduled interview (without candidate parameter)
     */
    public function storeDirect(Request $request, string $tenant)
    {
        $this->authorize('create', Interview::class);

        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'job_id' => 'nullable|exists:job_openings,id',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'mode' => 'required|in:onsite,remote,phone',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:2000',
            'panelists' => 'array',
            'panelists.*' => 'exists:users,id',
        ]);

        // Get the candidate
        $candidate = Candidate::findOrFail($request->candidate_id);

        // Find application if job_id is provided
        $applicationId = null;
        if ($request->job_id) {
            $application = $candidate->applications()
                ->where('job_opening_id', $request->job_id)
                ->first();
            $applicationId = $application?->id;
        }

        try {
            $interview = Interview::create([
                'tenant_id' => tenant_id(),
                'candidate_id' => $candidate->id,
                'job_id' => $request->job_id,
                'application_id' => $applicationId,
                'scheduled_at' => $request->scheduled_at,
                'duration_minutes' => $request->duration_minutes,
                'mode' => $request->mode,
                'location' => $request->location,
                'meeting_link' => $request->meeting_link,
                'created_by' => Auth::id(),
                'notes' => $request->notes,
                'status' => 'scheduled',
            ]);

            // Attach panelists
            if ($request->has('panelists')) {
                $interview->panelists()->attach($request->panelists);
            }

            // Send notifications
            $this->sendInterviewNotifications($interview, 'scheduled');
            
            Log::info('Interview scheduled directly', [
                'interview_id' => $interview->id,
                'candidate_id' => $candidate->id,
                'scheduled_at' => $interview->scheduled_at,
                'panelists_count' => $interview->panelists()->count()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Interview scheduled successfully',
                'interview' => $interview->load(['candidate', 'job', 'panelists'])
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to schedule interview directly', [
                'candidate_id' => $request->candidate_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule interview. Please try again.'
            ], 500);
        }
    }
}