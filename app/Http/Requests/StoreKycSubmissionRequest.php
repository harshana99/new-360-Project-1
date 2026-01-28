<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store KYC Submission Request
 * 
 * Validates KYC submission form data and uploaded documents
 */
class StoreKycSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Any authenticated user can submit KYC
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // ID Information
            'id_type' => ['required', 'in:nin,bvn,passport,driver_license,voter_card'],
            'id_number' => ['required', 'string', 'min:5', 'max:50'],
            
            // Document Uploads
            'id_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'], // 5MB
            'proof_of_address' => ['required', 'mimes:jpeg,png,jpg,pdf', 'max:5120'], // 5MB
            
            // Personal Information
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'date_of_birth' => [
                'required',
                'date',
                'before:' . now()->subYears(18)->format('Y-m-d'), // Must be 18+ years old
                'after:1900-01-01'
            ],
            'gender' => ['required', 'in:male,female,other'],
            
            // Optional Fields
            'occupation' => ['nullable', 'string', 'max:100'],
            'company_name' => ['nullable', 'string', 'max:100'],
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
            // ID Type
            'id_type.required' => 'Please select your ID type.',
            'id_type.in' => 'Invalid ID type selected.',
            
            // ID Number
            'id_number.required' => 'ID number is required.',
            'id_number.min' => 'ID number must be at least 5 characters.',
            'id_number.max' => 'ID number cannot exceed 50 characters.',
            
            // ID Image
            'id_image.required' => 'Please upload your ID image.',
            'id_image.image' => 'ID image must be a valid image file.',
            'id_image.mimes' => 'ID image must be in JPEG, PNG, or JPG format.',
            'id_image.max' => 'ID image must be less than 5MB.',
            
            // Proof of Address
            'proof_of_address.required' => 'Please upload proof of address.',
            'proof_of_address.mimes' => 'Proof of address must be in JPEG, PNG, JPG, or PDF format.',
            'proof_of_address.max' => 'Proof of address must be less than 5MB.',
            
            // Personal Information
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name cannot exceed 100 characters.',
            
            'last_name.required' => 'Last name is required.',
            'last_name.max' => 'Last name cannot exceed 100 characters.',
            
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Please enter a valid date.',
            'date_of_birth.before' => 'You must be at least 18 years old to register.',
            'date_of_birth.after' => 'Please enter a valid date of birth.',
            
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Invalid gender selection.',
            
            // Optional Fields
            'occupation.max' => 'Occupation cannot exceed 100 characters.',
            'company_name.max' => 'Company name cannot exceed 100 characters.',
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
            'id_type' => 'ID type',
            'id_number' => 'ID number',
            'id_image' => 'ID image',
            'proof_of_address' => 'proof of address',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'date_of_birth' => 'date of birth',
            'gender' => 'gender',
            'occupation' => 'occupation',
            'company_name' => 'company name',
        ];
    }
}
