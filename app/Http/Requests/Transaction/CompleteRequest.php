<?php

namespace App\Http\Requests\Transaction;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class CompleteRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'uuid' => "string"
    ])]
    public function rules(): array
    {
        return [
            'uuid' => 'required|uuid|exists:transactions,id'
        ];
    }

    /**
     * Get transaction model instance.
     *
     * @return Transaction
     */
    public function getTransaction(): Transaction
    {
        return Transaction::select(['id', 'status_id'])->findOrFail(
            $this->input('uuid')
        );
    }
}
