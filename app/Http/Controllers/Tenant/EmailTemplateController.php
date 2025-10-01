<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EmailTemplateController extends Controller
{
    public function index(string $tenant)
    {
        $templates = EmailTemplate::where('tenant_id', tenant_id())
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(20);

        $types = EmailTemplate::getTypes();

        return view('tenant.email-templates.index', compact('templates', 'types'));
    }

    public function create(string $tenant)
    {
        $types = EmailTemplate::getTypes();
        $selectedType = request('type', EmailTemplate::TYPE_CUSTOM);
        $variables = EmailTemplate::getVariablesForType($selectedType);

        return view('tenant.email-templates.create', compact('types', 'selectedType', 'variables'));
    }

    public function store(Request $request, string $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:' . implode(',', array_keys(EmailTemplate::getTypes())),
            'is_active' => 'boolean',
        ]);

        $validated['tenant_id'] = tenant_id();
        $validated['variables'] = EmailTemplate::getVariablesForType($validated['type']);
        $validated['is_active'] = $request->has('is_active');

        $template = EmailTemplate::create($validated);

        return redirect()
            ->route('tenant.email-templates.show', ['tenant' => $tenant, 'template' => $template])
            ->with('success', 'Email template created successfully.');
    }

    public function show(string $tenant, EmailTemplate $template)
    {
        $template->load('tenant');
        $variables = EmailTemplate::getVariablesForType($template->type);

        return view('tenant.email-templates.show', compact('template', 'variables'));
    }

    public function edit(string $tenant, EmailTemplate $template)
    {
        $types = EmailTemplate::getTypes();
        $variables = EmailTemplate::getVariablesForType($template->type);

        return view('tenant.email-templates.edit', compact('template', 'types', 'variables'));
    }

    public function update(Request $request, string $tenant, EmailTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:' . implode(',', array_keys(EmailTemplate::getTypes())),
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Update variables if type changed
        if ($template->type !== $validated['type']) {
            $validated['variables'] = EmailTemplate::getVariablesForType($validated['type']);
        }

        $template->update($validated);

        return redirect()
            ->route('tenant.email-templates.show', ['tenant' => $tenant, 'template' => $template])
            ->with('success', 'Email template updated successfully.');
    }

    public function destroy(string $tenant, EmailTemplate $template)
    {
        $template->delete();

        return redirect()
            ->route('tenant.email-templates.index', $tenant)
            ->with('success', 'Email template deleted successfully.');
    }

    public function preview(Request $request, string $tenant, EmailTemplate $template)
    {
        $variables = EmailTemplate::getVariablesForType($template->type);
        
        // Create sample data for preview
        $sampleData = [
            'candidate_name' => 'John Doe',
            'candidate_email' => 'john.doe@example.com',
            'job_title' => 'Senior Developer',
            'company_name' => tenant()->name ?? 'Your Company',
            'application_date' => now()->format('M j, Y'),
            'stage_name' => 'Interview',
            'previous_stage' => 'Screen',
            'message' => 'Thank you for your interest in this position.',
            'interview_date' => now()->addDays(7)->format('M j, Y'),
            'interview_time' => '2:00 PM',
            'interview_location' => 'Office or Video Call',
            'interviewer_name' => 'Jane Smith',
            'interview_notes' => 'Please bring your portfolio and be ready to discuss your experience.',
            'cancellation_reason' => 'Scheduling conflict',
        ];

        $subject = $template->subject;
        $body = $template->body;

        // Replace variables in subject and body
        foreach ($sampleData as $key => $value) {
            $subject = str_replace("{{$key}}", $value, $subject);
            $body = str_replace("{{$key}}", $value, $body);
        }

        return response()->json([
            'subject' => $subject,
            'body' => $body,
            'variables' => $variables,
        ]);
    }

    public function duplicate(string $tenant, EmailTemplate $template)
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Copy)';
        $newTemplate->is_active = false;
        $newTemplate->save();

        return redirect()
            ->route('tenant.email-templates.edit', ['tenant' => $tenant, 'template' => $newTemplate])
            ->with('success', 'Email template duplicated successfully.');
    }
}
