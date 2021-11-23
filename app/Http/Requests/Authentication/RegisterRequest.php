<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class RegisterRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'phone_number' => "string[]",
        'email' => "string",
        'password' => "string"
    ])]
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'regex:/^(\d{1,4})(\d{3})(\d{3})(\d{4})$/', 'unique:users,phone_number'],
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ];
    }
}
