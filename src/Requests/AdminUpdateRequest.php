<?php

namespace admin\admins\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateRequest extends FormRequest
{
    /**
     * Determine if the admin is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $adminId = $this->route('admin')->id ?? null;

        return [
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:100',
                Rule::unique('admins', 'email')->ignore($this->route('admin')->id)->whereNull('deleted_at'),
            ],
            'first_name' => 'required|string|min:3|max:100',
            'last_name'  => 'required|string|min:3|max:100',
            'mobile'     => 'required|digits_between:7,15',
            'status'     => 'required|in:0,1',
            'role_ids'   => 'required|array',
            'role_ids.*' => 'integer|exists:roles,id',
        ];
    }
    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'The email address is required.',
            'email.string'   => 'The email address must be a valid string.',
            'email.email'    => 'Please enter a valid email address.',
            'email.max'      => 'The email address cannot be longer than 100 characters.',
            'email.unique'   => 'This email address is already registered.',

            'first_name.required' => 'The first name is required.',
            'first_name.string'   => 'The first name must be a valid string.',
            'first_name.min'      => 'The first name must be at least 3 characters long.',
            'first_name.max'      => 'The first name cannot exceed 100 characters.',

            'last_name.required' => 'The last name is required.',
            'last_name.string'   => 'The last name must be a valid string.',
            'last_name.min'      => 'The last name must be at least 3 characters long.',
            'last_name.max'      => 'The last name cannot exceed 100 characters.',

            'mobile.required'        => 'The mobile number is required.',
            'mobile.digits_between'  => 'The mobile number must be between 7 and 15 digits.',

            'status.required' => 'The status field is required.',
            'status.in'       => 'The status must be either 0 (inactive) or 1 (active).',

            'role_ids.required'    => 'At least one role must be assigned.',
            'role_ids.array'       => 'The roles must be provided as an array.',
            'role_ids.*.integer'   => 'Each role ID must be a valid number.',
            'role_ids.*.exists'    => 'One or more selected roles are invalid.',
        ];
    }
}