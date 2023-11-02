<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterFormRequest extends FormRequest
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
            'username' => [
                'required',
                'string'
            ],
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique(User::class)->ignore($this->id),
            ],
            'password' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required'     => 'The Username field is required.',
            'email.required'        => 'The email field is required.',
            'password.required'     => 'The password field is required.',
        ];
    }
}
