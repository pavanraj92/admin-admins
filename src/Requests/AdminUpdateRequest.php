<?php

namespace admin\admins\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
                'max:100',
                'unique:admins,email,' . $this->route('admin')->id,
            ],
            'first_name' => 'nullable|string|min:3|max:100',
            'last_name' => 'nullable|string|min:3|max:100',
            'mobile' => 'required|regex:/^[0-9]{7,15}$/',
            'status' => 'required|in:0,1',
        ];
    }

    /**
     * Determine if the admin is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
