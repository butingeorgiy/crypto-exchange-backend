<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ModelExceptions\ExchangeEntity\NotFoundException as ExchangeEntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\CompleteRequest;
use App\Http\Requests\Transaction\CreateRequest;
use App\Http\Requests\Transaction\PrepareRequest;
use App\Jobs\EmailVerificationJob;
use App\Models\ExchangeEntity;
use App\Models\TransactionStatus;
use App\Models\User;
use App\Services\TransactionService\Client as TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Transaction service instance.
     *
     * @var TransactionService
     */
    protected TransactionService $transactionService;

    /**
     * Class constructor.
     *
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

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

        $preparationService = $this->transactionService->preparationService(
            $request->input('given_entity_id'),
            $givenEntityAmount ?? $request->input('given_entity_amount'),
            $request->input('received_entity_id'),
            $receiveEntityAmount ?? $request->input('received_entity_amount'),
            $request->input('inverted')
        );

        $transaction = $preparationService->save();

        return response()->json([
            'transaction_uuid' => $preparationService->getTransactionUuid(),
            'transaction_type' => $transaction->type,
            'next_step_url' => $preparationService->getNextStepUrl()
        ], options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Create transaction.
     *
     * @param CreateRequest $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function create(CreateRequest $request): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->guard('sanctum')->user();

        # If user isn't authenticated then create an account.
        if (is_null($user)) {
            $user = User::query()
                ->create([
                    'first_name' => $request->input('user_data.name'),
                    'phone_number' => $request->input('user_data.phone_number'),
                    'email' => $request->input('user_data.email'),
                    'password' => User::hashPassword($password = Str::random()),
                    'ref_code' => User::generateRefCode()
                ]);

            // TODO: mail password at user's E-mail address

            EmailVerificationJob::dispatch($user)->delay(now()->addSeconds(5));
        }

        # If user is authenticated but doesn't have set first name.
        elseif (is_null($user->first_name)) {
            $user->update([
                'first_name' => $request->input('user_data.name')
            ]);
        }

        $creationService = $this->transactionService->creationService($request->getTransactionModel(), $user);

        if ($request->hasAny('given_entity_amount', 'received_entity_amount')) {
            $creationService->updateAmount(
                $request->input('given_entity_amount'),
                $request->input('received_entity_amount')
            );
        }

        $clientResponse = $creationService->create(
            $request->input('options', [])
        );

        return response()->json([
            'success' => true,
            'message' => $creationService->getMessageForClient(),
            'transaction_response' => $clientResponse
        ], options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Complete transaction.
     *
     * @param CompleteRequest $request
     *
     * @return JsonResponse
     */
    public function complete(CompleteRequest $request): JsonResponse
    {
        $transaction = $request->getTransaction();

        if ($transaction->status_id === TransactionStatus::$PAYMENT_PENDING_STATUS_ID) {
            $transaction->update([
                'status_id' => TransactionStatus::$PENDING_STATUS_ID
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Пожалуйста ожидайте, с вами скоро свяжется наш менеджер'
        ], JSON_UNESCAPED_UNICODE);
    }
}
