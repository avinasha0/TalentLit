<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all tenants to create premade templates for each
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->createPremadeTemplates($tenant);
        }
    }

    private function createPremadeTemplates(Tenant $tenant): void
    {
        $premadeTemplates = [
            // Application Received Templates
            [
                'name' => 'Professional Application Confirmation',
                'type' => EmailTemplate::TYPE_APPLICATION_RECEIVED,
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
                'type' => EmailTemplate::TYPE_APPLICATION_RECEIVED,
                'subject' => 'We got your application! 🎉',
                'body' => "Hi {{ candidate_name }},

Great news! We've received your application for the {{ job_title }} position at {{ company_name }}.

We're excited to learn more about you and your experience. Our team will be reviewing applications over the next few days, and we'll be in touch soon if we'd like to move forward.

Thanks for considering {{ company_name }} as your next career opportunity!

Best,
The {{ company_name }} Team",
            ],

            // Stage Changed Templates
            [
                'name' => 'Formal Stage Update',
                'type' => EmailTemplate::TYPE_STAGE_CHANGED,
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
                'type' => EmailTemplate::TYPE_STAGE_CHANGED,
                'subject' => 'Great news! Your application is moving forward 🚀',
                'body' => "Hi {{ candidate_name }},

Exciting update! Your application for the {{ job_title }} position at {{ company_name }} has progressed from {{ previous_stage }} to {{ stage_name }}.

This means our team was impressed with your background and wants to learn more about you. We'll be reaching out soon with details about what comes next.

Keep up the great work!

Best regards,
The {{ company_name }} Team",
            ],

            // Interview Scheduled Templates
            [
                'name' => 'Professional Interview Invitation',
                'type' => EmailTemplate::TYPE_INTERVIEW_SCHEDULED,
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
                'type' => EmailTemplate::TYPE_INTERVIEW_SCHEDULED,
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

            // Interview Canceled Templates
            [
                'name' => 'Professional Interview Cancellation',
                'type' => EmailTemplate::TYPE_INTERVIEW_CANCELED,
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
                'type' => EmailTemplate::TYPE_INTERVIEW_CANCELED,
                'subject' => 'Interview Update - {{ job_title }}',
                'body' => "Hi {{ candidate_name }},

Unfortunately, we need to reschedule your interview for the {{ job_title }} position at {{ company_name }}.

{{ cancellation_reason }}

We know this is frustrating, and we're really sorry for the inconvenience. We'll reach out soon to find a new time that works for everyone.

Thanks for your patience!

Best,
The {{ company_name }} Team",
            ],

            // Interview Updated Templates
            [
                'name' => 'Professional Interview Update',
                'type' => EmailTemplate::TYPE_INTERVIEW_UPDATED,
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
                'type' => EmailTemplate::TYPE_INTERVIEW_UPDATED,
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

            // Custom Templates
            [
                'name' => 'General Communication Template',
                'type' => EmailTemplate::TYPE_CUSTOM,
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
                'type' => EmailTemplate::TYPE_CUSTOM,
                'subject' => 'Following up on your {{ job_title }} application',
                'body' => "Hi {{ candidate_name }},

We wanted to follow up on your application for the {{ job_title }} position at {{ company_name }}.

[Your follow-up message here]

We appreciate your patience and look forward to hearing from you.

Best,
The {{ company_name }} Team",
            ],
        ];

        foreach ($premadeTemplates as $templateData) {
            // Check if this template already exists for this tenant
            $existingTemplate = EmailTemplate::where('tenant_id', $tenant->id)
                ->where('name', $templateData['name'])
                ->where('type', $templateData['type'])
                ->first();

            if (!$existingTemplate) {
                EmailTemplate::create([
                    'tenant_id' => $tenant->id,
                    'name' => $templateData['name'],
                    'subject' => $templateData['subject'],
                    'body' => $templateData['body'],
                    'type' => $templateData['type'],
                    'is_active' => true,
                    'variables' => EmailTemplate::getVariablesForType($templateData['type']),
                ]);
            }
        }
    }
}
