<?php

namespace App\Http\Requests\Transaction;

use App\Models\ExchangeDirection;
use App\Services\TransactionService\Client as TransactionServiceClient;
use App\Services\TransactionService\ComplexValidator as TransactionComplexValidator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use JetBrains\PhpStorm\ArrayShape;

class PrepareRequest extends FormRequest
{
    /**
     * @inheritdoc
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Transaction validator instance.
     *
     * @var TransactionComplexValidator
     */
    protected TransactionComplexValidator $complexValidator;

    /**
     * Request's rules.
     *
     * @return array
     */
    #[ArrayShape([
        'given_entity_id' => "string",
        'received_entity_id' => "string",
        'given_entity_amount' => "string",
        'received_entity_amount' => "string",
        'inverted' => "string"
    ])]
    public function rules(): array
    {
        return [
            'given_entity_id' => 'required|exists:exchange_entities,id',
            'given_entity_amount' => 'required_without:received_entity_amount|numeric|min:0',
            'received_entity_id' => 'required|exists:exchange_entities,id',
            'received_entity_amount' => 'required_without:given_entity_amount|numeric|min:0',
            'inverted' => 'required|boolean'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     *
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function withValidator(Validator $validator): void
    {
        if ($validator->errors()->any()) {
            return;
        }

        $this->complexValidator = app()->make(TransactionServiceClient::class)->validator();

        $validator->after(function (Validator $validator) {
            $givenEntityId = $this->input('given_entity_id');
            $givenEntityAmount = $this->input('given_entity_amount');

            $receivedEntityId = $this->input('received_entity_id');
            $receivedEntityAmount = $this->input('received_entity_amount');

            if ($givenEntityAmount !== null && $receivedEntityAmount !== null) {
                $validator->errors()->add(
                    'transfer_entities',
                    'Кол-во для обмена можно указать только одной позиции.'
                );

                return;
            }

            if (!$this->complexValidator->hasDirection($givenEntityId, $receivedEntityId)) {
                $validator->errors()->add(
                    'transfer_direction',
                    'Обмен между указанными позициями невозможен.'
                );

                return;
            }

            $canPrepare = $this->complexValidator
                ->canUserPrepareTransaction(
                    directionId: $this->getDirectionId(),
                    inverted: $this->input('inverted'),
                    givenEntityAmount: $givenEntityAmount,
                    receivedEntityAmount: $receivedEntityAmount,
                    userId: auth()->guard('sanctum')->id()
                );

            if (!$canPrepare) {
                $validator->errors()->add(
                    'transfer_direction',
                    'Транзакция невозможна из-за нарушения установленных лимитов.'
                );
            }
        });
    }

    /**
     * Get direction ID.
     *
     * @return int|null
     */
    public function getDirectionId(): ?int
    {
        return ExchangeDirection::getIdByEntities(
            $this->input('given_entity_id'),
            $this->input('received_entity_id')
        );
    }
}
