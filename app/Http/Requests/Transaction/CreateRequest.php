<?php

namespace App\Http\Requests\Transaction;

use App\Models\ExchangeDirection;
use App\Models\Transaction;
use App\Services\TransactionService\Client as TransferServiceClient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CreateRequest extends FormRequest
{
    /**
     * @inheritdoc
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'uuid' => "string",
        'given_entity_amount' => "string",
        'received_entity_amount' => "string",
        'user_data' => "string",
        'user_data.name' => "string",
        'user_data.email' => "string",
        'user_data.phone_number' => "string[]"
    ])]
    public function rules(): array
    {
        return [
            'uuid' => 'required|uuid|exists:transactions,id',
            'given_entity_amount' => 'nullable|numeric|min:0',
            'received_entity_amount' => 'nullable|numeric|min:0',
            'user_data' => 'nullable|array',
            'user_data.name' => 'required_with:user_data|string|max:255',
            'user_data.email' => 'required_with:user_data|string|email|max:255|unique:users,email',
            'user_data.phone_number' => [
                'required_with:user_data',
                'regex:/^(\d{1,4})(\d{3})(\d{3})(\d{4})$/',
                'unique:users,phone_number'
            ]
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     *
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (Auth::guest() && !$this->has('user_data')) {
                $validator->errors()->add(
                    'user_data',
                    'Необходимо указать данные пользователя.'
                );

                return;
            }

            /** @var Transaction $transaction */
            $transaction = Transaction::query()
                ->select([
                    'id', 'meta_direction_id', 'meta_inverted'
                ])
                ->findOrFail($this->input('uuid'));

            /** @var ExchangeDirection $direction */
            $direction = $transaction->getMetaDirection(['id']);

            if (is_null($direction)) {
                throw new HttpException(
                    400,
                    'Направление транзакции скорее всего было отключено ' .
                    'администрацией сайта. Попробуйте создать транзакцию снова.'
                );
            }

            $givenEntityAmount = $this->input('given_entity_amount');
            $receivedEntityAmount = $this->input('received_entity_amount');

            if ($givenEntityAmount !== null && $receivedEntityAmount !== null) {
                $validator->errors()->add(
                    'transfer_entities',
                    'Кол-во для обмена можно указать только одной позиции.'
                );

                return;
            }

            if ($givenEntityAmount !== null || $receivedEntityAmount !== null) {
                $canPrepare = TransferServiceClient::validator()
                    ->canUserPrepareTransaction(
                        directionId: $direction->id,
                        inverted: $transaction->getMetaInverted(),
                        givenEntityAmount: $givenEntityAmount,
                        receivedEntityAmount: $receivedEntityAmount,
                        userId: Auth::id()
                    );

                if (!$canPrepare) {
                    $validator->errors()->add(
                        'transfer_direction',
                        'Транзакция невозможна из-за нарушения установленных лимитов.'
                    );
                }
            }
        });
    }
}