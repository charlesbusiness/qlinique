<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'password' => ['nullable', Password::defaults(), 'confirmed'],
            'password_confirmation' => ['nullable'],
            'role' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($value === UserRole::SuperAdmin->value) {
                    $fail('The super admin role cannot be assigned through this form.');
                }
                if (! UserRole::tryFrom($value)) {
                    $fail('The selected role is invalid.');
                }
            }],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ];
    }
}
