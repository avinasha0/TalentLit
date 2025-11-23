<?php

namespace App\Http\Requests;

use App\Rules\RecaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class ApplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                // Note: We don't validate uniqueness here because we handle it in the action
                // to allow reusing existing candidates
            ],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'resume' => [
                'required',
                'file',
                'max:5120', // 5MB
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
            'consent' => ['required', 'accepted'],
            'g-recaptcha-response' => ['required', new RecaptchaRule(app(\App\Services\RecaptchaService::class), $this)],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'An application with this email already exists.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must be exactly 10 digits.',
            'resume.required' => 'Resume is required.',
            'resume.file' => 'Resume must be a valid file.',
            'resume.max' => 'Resume file size must not exceed 5MB.',
            'resume.mimetypes' => 'Resume must be a PDF, DOC, or DOCX file.',
            'consent.required' => 'You must agree to the terms and conditions.',
            'consent.accepted' => 'You must agree to the terms and conditions.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => trim($this->first_name),
            'last_name' => trim($this->last_name),
            'email' => strtolower(trim($this->email)),
            'phone' => $this->phone ? trim($this->phone) : null,
        ]);
    }
}
