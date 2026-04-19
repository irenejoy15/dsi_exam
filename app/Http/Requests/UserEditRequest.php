<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:users,id',
            'name_update' => 'required|string|max:255',
            'email_update' => 'required|string|email|max:255|unique:users,email,' . $this->input('id'),
            'password_update' => 'nullable|string|min:8',
            'is_admin_update' => 'required|boolean',
            'is_active_update' => 'required|boolean',
            'photo_update' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address_update' => 'nullable|string|max:255',
            'phone_update' => 'nullable|string|max:20',
        ];
    }
}
