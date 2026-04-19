<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'update_id' => 'required|exists:products,id',
            'update_name' => 'required|string|max:255',
            'update_description' => 'nullable|string',
            'update_price' => 'required|numeric|min:0',
            'update_category_id' => 'required|exists:categories,id',
            'update_stock' => 'required|integer|min:0',
            'update_is_active' => 'required|boolean',
        ];
    }
}
