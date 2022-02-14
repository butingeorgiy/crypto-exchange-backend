<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ModelExceptions\ExchangeEntity\NotFoundException as ExchangeEntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\CreateRequest;
use App\Http\Requests\Transaction\PrepareRequest;
use App\Jobs\EmailVerificationJob;
use App\Models\ExchangeEntity;
use App\Models\User;
use App\Services\TransactionService\Client as TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Prepare transaction.
     *
     * @param PrepareRequest $request
     *
     * @return JsonResponse
     *
     * @throws ExchangeEntityNotFoundException
     */
    public function prepare(PrepareRequest $request): JsonResponse
    {
        /**
         * @var ExchangeEntity $receiveEntity
         * @var ExchangeEntity $givenEntity
         */

        if ($request->has('given_entity_amount')) {
            $receiveEntity = ExchangeEntity::query()
                ->select(['id', 'cost'])
                ->find($request->input('received_entity_id'));

            $receiveEntityAmount = $receiveEntity->calculateEquivalentOfAnotherEntity(
                $request->input('given_entity_id'),
                $request->input('given_entity_amount')
            );
        } else if ($request->has('received_entity_amount')) {
            $givenEntity = ExchangeEntity::query()
                ->select(['id', 'cost'])
                ->find($request->input('given_entity_id'));

            $givenEntityAmount = $givenEntity->calculateEquivalentOfAnotherEntity(
                $request->input('received_entity_id'),
                $request->input('received_entity_amount')
            );
        }

        $transactionService = TransactionService::prepare(
            $request->input('given_entity_id'),
            $givenEntityAmount ?? $request->input('given_entity_amount'),
            $request->input('received_entity_id'),
            $receiveEntityAmount ?? $request->input('received_entity_amount'),
            $request->input('inverted')
        );

        $transaction = $transactionService->save();

        return response()->json([
            'transaction_uuid' => $transactionService->getTransactionUuid(),
            'transaction_type' => $transaction->type,
            'next_step_url' => $transactionService->getNextStepUrl()
        ], options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Create transaction.
     *
     * @param CreateRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        if (Auth::guest()) {
            $user = User::query()
                ->create([
                    'first_name' => $request->input('user_data.name'),
                    'phone_number' => $request->input('user_data.phone_number'),
                    'email' => $request->input('user_data.email')
                ]);

            EmailVerificationJob::dispatch($user)->delay(now()->addSeconds(5));
        } else {
            $user = Auth::user();
        }



        return response()->json([
            'success' => true
        ], options: JSON_UNESCAPED_UNICODE);
    }
}