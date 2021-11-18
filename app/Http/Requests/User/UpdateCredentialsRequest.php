<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Services\AuthenticationService\Client as AuthenticationService;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class UpdateCredentialsRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'email' => "array",
        'current_email_code' => "string",
        'new_email_code' => "string",
        'current_password' => "string",
        'new_password' => "string",
        'new_password_confirmation' => "string"
    ])]
    public function rules(): array
    {
        return [
            'email' => [
                'required_without:new_password',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    $userId = app(AuthenticationService::class)->currentUserId();

                    $isExist = User::where([
                        ['email', $value],
                        ['id', '!=', $userId]
                    ])->exists();

                    if ($isExist) $fail(trans('validation.unique', ['attribute' => $attribute]));
                }
            ],
            'current_email_code' => 'required_with:email|string|size:6',
            'new_email_code' => 'required_with:email|string|size:6',
            'current_password' => 'required|string|min:6',
            'new_password' => 'required_without:email|string|min:8|confirmed',
            'new_password_confirmation' => 'required_with:password'
        ];
    }
}
