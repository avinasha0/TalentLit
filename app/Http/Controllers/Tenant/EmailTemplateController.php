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
        
        // Get premade templates for the selected type
        $premadeTemplates = $this->getPremadeTemplates($selectedType);

        return view('tenant.email-templates.create', compact('types', 'selectedType', 'variables', 'premadeTemplates'));
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

    /**
     * Get premade templates for a specific type
     */
    private function getPremadeTemplates(string $type): array
    {
        $premadeTemplates = [
            EmailTemplate::TYPE_APPLICATION_RECEIVED => [
                [
                    'name' => 'Professional Application Confirmation',
                    'subject' => 'Thank you for your application - {{ job_title }}',
                    'body' => "Dear {{ candidate_name }},

Thank you for your interest in the {{ job_title }} position at {{ company_name }}. We have successfully received your application and resume.

Our hiring team will review your application and qualifications. If your background matches our requirements, we will contact you within the next 5-7 business days to discuss the next steps.

We appreciate your interest in joining our team and look forward to learning more about your experience.

Best regards,
{{ company_name }} Hiring Team",
                ],
                [
                    'name' => 'Friendly Application Acknowledgment',
                    'subject' => 'We got your application! 🎉',
                    'body' => "Hi {{ candidate_name }},

Great news! We've received your application for the {{ job_title }} position at {{ company_name }}.

We're excited to learn more about you and your experience. Our team will be reviewing applications over the next few days, and we'll be in touch soon if we'd like to move forward.

Thanks for considering {{ company_name }} as your next career opportunity!

Best,
The {{ company_name }} Team",
                ],
            ],
            EmailTemplate::TYPE_STAGE_CHANGED => [
                [
                    'name' => 'Formal Stage Update',
                    'subject' => 'Application Update - {{ job_title }}',
                    'body' => "Dear {{ candidate_name }},

We are writing to inform you about the status of your application for the {{ job_title }} position at {{ company_name }}.

Your application has moved from {{ previous_stage }} to {{ stage_name }}. This indicates that our hiring team has reviewed your qualifications and would like to proceed with the next phase of our selection process.

We will contact you shortly with further details about the next steps.

Thank you for your continued interest in this position.

Sincerely,
{{ company_name }} Hiring Team",
                ],
                [
                    'name' => 'Encouraging Stage Progress',
                    'subject' => 'Great news! Your application is moving forward 🚀',
                    'body' => "Hi {{ candidate_name }},

Exciting update! Your application for the {{ job_title }} position at {{ company_name }} has progressed from {{ previous_stage }} to {{ stage_name }}.

This means our team was impressed with your background and wants to learn more about you. We'll be reaching out soon with details about what comes next.

Keep up the great work!

Best regards,
The {{ company_name }} Team",
                ],
            ],
            EmailTemplate::TYPE_INTERVIEW_SCHEDULED => [
                [
                    'name' => 'Professional Interview Invitation',
                    'subject' => 'Interview Invitation - {{ job_title }}',
                    'body' => "Dear {{ candidate_name }},

We are pleased to invite you for an interview for the {{ job_title }} position at {{ company_name }}.

Interview Details:
• Date: {{ interview_date }}
• Time: {{ interview_time }}
• Location: {{ interview_location }}
• Interviewer: {{ interviewer_name }}

{{ interview_notes }}

Please confirm your attendance by replying to this email. If you need to reschedule, please contact us as soon as possible.

We look forward to meeting you and learning more about your qualifications.

Best regards,
{{ company_name }} Hiring Team",
                ],
                [
                    'name' => 'Casual Interview Invitation',
                    'subject' => 'Let\'s chat! Interview scheduled for {{ job_title }}',
                    'body' => "Hi {{ candidate_name }},

We'd love to meet you! We're excited to invite you for an interview for the {{ job_title }} position at {{ company_name }}.

Here are the details:
📅 Date: {{ interview_date }}
🕐 Time: {{ interview_time }}
📍 Location: {{ interview_location }}
👤 Interviewer: {{ interviewer_name }}

{{ interview_notes }}

Please let us know if this time works for you, or if you need to reschedule.

Looking forward to meeting you!

Best,
The {{ company_name }} Team",
                ],
            ],
            EmailTemplate::TYPE_INTERVIEW_CANCELED => [
                [
                    'name' => 'Professional Interview Cancellation',
                    'subject' => 'Interview Cancellation - {{ job_title }}',
                    'body' => "Dear {{ candidate_name }},

We regret to inform you that we need to cancel your scheduled interview for the {{ job_title }} position at {{ company_name }}.

{{ cancellation_reason }}

We sincerely apologize for any inconvenience this may cause. We will contact you soon to reschedule the interview at a more convenient time.

Thank you for your understanding and continued interest in this position.

Best regards,
{{ company_name }} Hiring Team",
                ],
                [
                    'name' => 'Understanding Interview Cancellation',
                    'subject' => 'Interview Update - {{ job_title }}',
                    'body' => "Hi {{ candidate_name }},

Unfortunately, we need to reschedule your interview for the {{ job_title }} position at {{ company_name }}.

{{ cancellation_reason }}

We know this is frustrating, and we're really sorry for the inconvenience. We'll reach out soon to find a new time that works for everyone.

Thanks for your patience!

Best,
The {{ company_name }} Team",
                ],
            ],
            EmailTemplate::TYPE_INTERVIEW_UPDATED => [
                [
                    'name' => 'Professional Interview Update',
                    'subject' => 'Interview Details Updated - {{ job_title }}',
                    'body' => "Dear {{ candidate_name }},

We are writing to inform you of updated details for your interview for the {{ job_title }} position at {{ company_name }}.

Updated Interview Details:
• Date: {{ interview_date }}
• Time: {{ interview_time }}
• Location: {{ interview_location }}
• Interviewer: {{ interviewer_name }}

{{ interview_notes }}

Please confirm that these new details work for you by replying to this email.

Thank you for your flexibility and continued interest in this position.

Best regards,
{{ company_name }} Hiring Team",
                ],
                [
                    'name' => 'Friendly Interview Update',
                    'subject' => 'Quick update on your interview for {{ job_title }}',
                    'body' => "Hi {{ candidate_name }},

Just a quick update on your interview for the {{ job_title }} position at {{ company_name }}!

Here are the updated details:
📅 Date: {{ interview_date }}
🕐 Time: {{ interview_time }}
📍 Location: {{ interview_location }}
👤 Interviewer: {{ interviewer_name }}

{{ interview_notes }}

Please let us know if this works for you!

Thanks,
The {{ company_name }} Team",
                ],
            ],
            EmailTemplate::TYPE_CUSTOM => [
                [
                    'name' => 'General Communication Template',
                    'subject' => 'Important Update - {{ job_title }}',
                    'body' => "Dear {{ candidate_name }},

We hope this message finds you well. We wanted to reach out regarding your application for the {{ job_title }} position at {{ company_name }}.

[Your custom message here]

If you have any questions, please don't hesitate to contact us.

Best regards,
{{ company_name }} Team",
                ],
                [
                    'name' => 'Follow-up Template',
                    'subject' => 'Following up on your {{ job_title }} application',
                    'body' => "Hi {{ candidate_name }},

We wanted to follow up on your application for the {{ job_title }} position at {{ company_name }}.

[Your follow-up message here]

We appreciate your patience and look forward to hearing from you.

Best,
The {{ company_name }} Team",
                ],
            ],
        ];

        return $premadeTemplates[$type] ?? [];
    }

    /**
     * Load a premade template via AJAX
     */
    public function loadPremadeTemplate(Request $request, string $tenant)
    {
        $type = $request->input('type');
        $templateIndex = $request->input('template_index');
        
        $premadeTemplates = $this->getPremadeTemplates($type);
        
        if (isset($premadeTemplates[$templateIndex])) {
            return response()->json($premadeTemplates[$templateIndex]);
        }
        
        return response()->json(['error' => 'Template not found'], 404);
    }
}
