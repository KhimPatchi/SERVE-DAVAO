<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizerVerificationRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|in:non_profit,school,community,business,individual,other',
            'identification_number' => 'required|string|max:50|regex:/^[A-Za-z0-9\-]+$/',
            'identification_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'address' => 'required|string|max:500',
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^[\d+\-\s()]+$/',
                function ($attribute, $value, $fail) {
                    $cleanDigits = preg_replace('/[^\d]/', '', $value);
                    $digitCount = strlen($cleanDigits);
                    
                    if ($digitCount < 10) {
                        $fail('The phone number must contain at least 10 digits.');
                    }
                    if ($digitCount > 15) {
                        $fail('The phone number cannot exceed 15 digits.');
                    }
                    if (preg_match('/[a-zA-Z]/', $value)) {
                        $fail('The phone number cannot contain letters.');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'The phone number can only contain numbers, plus sign, hyphens, spaces, and parentheses.',
            'identification_number.regex' => 'The ID number can only contain letters, numbers, and hyphens.',
            'organization_type.in' => 'Please select a valid organization type.',
            'identification_document.mimes' => 'The document must be a JPG, PNG, or PDF file.',
            'identification_document.max' => 'The document must not exceed 2MB in size.',
        ];
    }
}