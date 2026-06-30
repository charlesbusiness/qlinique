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
            'age' => ['required', 'integer', 'min:0', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'account_type' => ['required', 'string', 'in:individual,family,corporate'],
            'patient_type' => ['nullable', 'string', 'in:admission,outpatient,outreach'],
            'family_file_id' => ['nullable', 'exists:family_files,id'],
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

    public function messages(): array
    {
        return [
            'name.required' => 'The patient name is required.',
            'gender.required' => 'The gender is required.',
            'age.required' => 'The age is required.',
            'account_type.required' => 'The account type is required.',
            'account_type.in' => 'The selected account type is invalid.',
            'patient_type.in' => 'The selected patient type is invalid.',
            'family_file_id.exists' => 'The selected family file does not exist.',
            'next_of_kin.array' => 'The next of kin must be an array.',
            'next_of_kin.name.string' => 'The next of kin name must be a string.',
            'next_of_kin.relationship.string' => 'The next of kin relationship must be a string.',
            'next_of_kin.phone.string' => 'The next of kin phone must be a string.',
            'next_of_kin.address.string' => 'The next of kin address must be a string.',
            'consent.array' => 'The consent must be an array.',
            'consent.treatment.boolean' => 'The consent treatment must be true or false.',
            'consent.privacy.boolean' => 'The consent privacy must be true or false.',
            'consent.signed_at.date' => 'The consent signed at must be a valid date.',
            'signature_type.in' => 'The selected signature type is invalid.',
            'signature_upload.image' => 'The signature upload must be an image.',
            'signature_upload.max' => 'The signature upload may not be greater than 2MB.',
        ];
    }
}
