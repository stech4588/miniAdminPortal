<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyCreateFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string'
            ],
            'email' => [
                'nullable',
                'string'
            ],
            'website' => [
                'nullable',
                'string'
            ],
            'logo' => [
                'nullable',
                'image', // Check if it's an image file
                'dimensions:min_width=100,min_height=100', // Minimum dimensions
            ],
        ];
    }
}
