<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tenantId = tenant_id();
        $candidateId = $this->route('candidate')?->id;

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'primary_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('candidates')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })->ignore($candidateId),
            ],
            'primary_phone' => 'nullable|string|max:20',
            'source' => 'nullable|string|max:255',
            'resume_raw_text' => 'nullable|string',
            'resume_json' => 'nullable|array',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'primary_email.unique' => 'A candidate with this email already exists in this organization.',
        ];
    }
}
