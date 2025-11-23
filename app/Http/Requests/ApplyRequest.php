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
            'current_ctc' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'expected_ctc' => ['required', 'numeric', 'min:0', 'max:999999.99'],
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
            'current_ctc.required' => 'Current CTC Per Year is required.',
            'current_ctc.numeric' => 'Current CTC Per Year must be a valid number.',
            'current_ctc.min' => 'Current CTC Per Year must be greater than or equal to 0.',
            'current_ctc.max' => 'Current CTC Per Year must not exceed 999999.99.',
            'expected_ctc.required' => 'Expected CTC Per Year is required.',
            'expected_ctc.numeric' => 'Expected CTC Per Year must be a valid number.',
            'expected_ctc.min' => 'Expected CTC Per Year must be greater than or equal to 0.',
            'expected_ctc.max' => 'Expected CTC Per Year must not exceed 999999.99.',
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
            'current_ctc' => $this->has('current_ctc') && $this->current_ctc !== null && $this->current_ctc !== '' ? (float) $this->current_ctc : null,
            'expected_ctc' => $this->has('expected_ctc') && $this->expected_ctc !== null && $this->expected_ctc !== '' ? (float) $this->expected_ctc : null,
        ]);
    }
}
