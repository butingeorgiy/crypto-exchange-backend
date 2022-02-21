<?php

namespace App\Http\Requests\Verification;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;

class CreateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'first_name' => "string",
        'last_name' => "string",
        'middle_name' => "string",
        'phone_number' => "array",
        'telegram_login' => "string",
        'selfie_attachment' => "string",
        'scan_attachment' => "string"
    ])]
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone_number' => [
                'required',
                'regex:/^(\d{1,4})(\d{3})(\d{3})(\d{4})$/',
                function ($attribute, $value, $fail) {
                    $isExist = User::where([
                        ['id', '!=', Auth::id()],
                        ['phone_number', $value]
                    ])->exists();

                    if ($isExist) $fail(trans('validation.unique', ['attribute' => $attribute]));
                }
            ],
            'telegram_login' => [
                'required',
                'string',
                'regex:/^@\w{5,30}$/'
            ],
            'selfie_attachment' => 'required|file|max:2000|mimes:jpeg,png,pdf',
            'scan_attachment' => 'required|file|max:2000|mimes:jpeg,png,pdf'
        ];
    }
}
