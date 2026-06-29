<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'account_type' => ['required', 'string', 'in:individual,family,corporate'],
            'account_holder_id' => ['nullable', 'exists:patients,id'],
            'next_of_kin' => ['nullable', 'array'],
            'next_of_kin.name' => ['nullable', 'string', 'max:255'],
            'next_of_kin.relationship' => ['nullable', 'string', 'max:255'],
            'next_of_kin.phone' => ['nullable', 'string', 'max:20'],
            'next_of_kin.address' => ['nullable', 'string', 'max:1000'],
            'consent' => ['nullable', 'array'],
            'consent.treatment' => ['nullable', 'boolean'],
            'consent.privacy' => ['nullable', 'boolean'],
            'consent.signed_at' => ['nullable', 'date'],
            'religion' => ['nullable', 'string', 'max:255'],
            'denomination' => ['nullable', 'string', 'max:255'],
            'signature_type' => ['nullable', 'string', 'in:typed,drawn,uploaded'],
            'signature' => ['nullable', 'string'],
            'signature_upload' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
