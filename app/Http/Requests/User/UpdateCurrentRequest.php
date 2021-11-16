<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Services\AuthenticationService\Client as AuthenticationClient;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class UpdateCurrentRequest extends FormRequest
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
        'phone_number' => "array"
    ])]
    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone_number' => [
                'nullable',
                'regex:/^(\d{1,4})(\d{3})(\d{3})(\d{4})$/',
                function ($attribute, $value, $fail) {
                    $userId = app(AuthenticationClient::class)->currentUserId();

                    $isExist = User::where([
                        ['id', '!=', $userId],
                        ['phone_number', $value]
                    ])->exists();

                    if ($isExist) $fail(trans('validation.unique', ['attribute' => $attribute]));
                }
            ]
        ];
    }
}
