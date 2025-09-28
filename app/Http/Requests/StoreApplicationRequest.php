<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
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
        $tenantId = currentTenant()?->id;

        return [
            'job_opening_id' => [
                'required',
                'uuid',
                Rule::exists('job_openings', 'id')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                }),
            ],
            'candidate_id' => [
                'required',
                'uuid',
                Rule::exists('candidates', 'id')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                }),
            ],
            'status' => 'nullable|string|in:active,withdrawn,hired,rejected',
            'current_stage_id' => [
                'nullable',
                'uuid',
                Rule::exists('job_stages', 'id')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                }),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'job_opening_id.exists' => 'The selected job opening does not exist in this organization.',
            'candidate_id.exists' => 'The selected candidate does not exist in this organization.',
            'current_stage_id.exists' => 'The selected stage does not exist in this organization.',
        ];
    }
}
