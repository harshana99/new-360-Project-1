<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Approve KYC Submission Request
 * 
 * Validates admin approval/rejection of KYC submissions
 */
class ApproveKycSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if user is admin or has 'approve_kyc' permission
        $user = auth()->user();
        
        return $user && (
            $user->hasRole('admin') || 
            $user->can('approve_kyc')
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'approval_status' => [
                'required',
                'in:approved,rejected,resubmission_required'
            ],
            'admin_notes' => [
                'nullable',
                'string',
                'max:500'
            ],
            'rejection_reason' => [
                'required_if:approval_status,rejected',
                'nullable',
                'string',
                'max:300'
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'approval_status.required' => 'Please select an approval status.',
            'approval_status.in' => 'Invalid approval status selected.',
            
            'admin_notes.max' => 'Admin notes cannot exceed 500 characters.',
            
            'rejection_reason.required_if' => 'Rejection reason is required when rejecting KYC.',
            'rejection_reason.max' => 'Rejection reason cannot exceed 300 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'approval_status' => 'approval status',
            'admin_notes' => 'admin notes',
            'rejection_reason' => 'rejection reason',
        ];
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        abort(403, 'You are not authorized to approve KYC submissions.');
    }
}
