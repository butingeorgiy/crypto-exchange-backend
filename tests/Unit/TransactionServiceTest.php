<?php

namespace Tests\Unit;

use App\Exceptions\ModelExceptions\ExchangeDirection\NotFoundException as ExchangeDirectionNotFoundException;
use App\Exceptions\ModelExceptions\ExchangeEntity\NotFoundException as ExchangeEntityNotFoundException;
use App\Exceptions\ModelExceptions\User\NotFoundException as UserNotFoundException;
use App\Services\TransactionService\Client as TransactionServiceClient;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    /**
     * Check hasDirection() validator method.
     *
     * @return void
     */
    public function test_has_direction_validator_method(): void
    {
        $validator = TransactionServiceClient::validator();

        $this->assertTrue($validator->hasDirection(1, 2));
        $this->assertTrue($validator->hasDirection(2, 1));

        $this->assertFalse($validator->hasDirection(123, 321));
        $this->assertFalse($validator->hasDirection(4, 3));
    }

    /**
     * Check canUserPrepareTransaction() validator method.
     *
     * @return void
     *
     * @throws ExchangeDirectionNotFoundException
     * @throws ExchangeEntityNotFoundException
     * @throws UserNotFoundException
     */
    public function test_can_user_prepare_transaction_validator_method(): void
    {
        $validator = TransactionServiceClient::validator();

        # Check minimal limit for not authenticated user and not inverted direction.

        $this->assertTrue($validator->canUserPrepareTransaction(1, false, 20_000));
        $this->assertFalse($validator->canUserPrepareTransaction(1, false, 19_999));

        $this->assertTrue($validator->canUserPrepareTransaction(1, false, receivedEntityAmount: 46));
        $this->assertFalse($validator->canUserPrepareTransaction(1, false, receivedEntityAmount: 45));

        # Check minimal limit for not authenticated user and inverted direction.

        $this->assertTrue($validator->canUserPrepareTransaction(1, true, 46));
        $this->assertFalse($validator->canUserPrepareTransaction(1, true, 45));

        $this->assertTrue($validator->canUserPrepareTransaction(1, true, receivedEntityAmount: 20_000));
        $this->assertFalse($validator->canUserPrepareTransaction(1, true, receivedEntityAmount: 19_999));

        # Check maximal limit for not authenticated user and not inverted direction.

        $this->assertTrue($validator->canUserPrepareTransaction(1, false, 48_966));
        $this->assertFalse($validator->canUserPrepareTransaction(1, false, 48_967));

        $this->assertTrue($validator->canUserPrepareTransaction(1, false, receivedEntityAmount: 112));
        $this->assertFalse($validator->canUserPrepareTransaction(1, false, receivedEntityAmount: 113));

        # Check maximal limit for not authenticated user and inverted direction.

        $this->assertTrue($validator->canUserPrepareTransaction(1, true, 112));
        $this->assertFalse($validator->canUserPrepareTransaction(1, true, 113));

        $this->assertTrue($validator->canUserPrepareTransaction(1, true, receivedEntityAmount: 48_966));
        $this->assertFalse($validator->canUserPrepareTransaction(1, true, receivedEntityAmount: 48_967));

        # Check maximal limit for not verified user and not inverted direction.

        $this->assertTrue($validator->canUserPrepareTransaction(1, false, 148_643, userId: 2));
        $this->assertFalse($validator->canUserPrepareTransaction(1, false, 148_644, userId: 2));

        $this->assertTrue($validator->canUserPrepareTransaction(1, false, receivedEntityAmount: 340, userId: 2));
        $this->assertFalse($validator->canUserPrepareTransaction(1, false, receivedEntityAmount: 341, userId: 2));

        # Check maximal limit for not verified user and inverted direction.

        $this->assertTrue($validator->canUserPrepareTransaction(1, true, 340, userId: 2));
        $this->assertFalse($validator->canUserPrepareTransaction(1, true, 341, userId: 2));

        $this->assertTrue($validator->canUserPrepareTransaction(1, true, receivedEntityAmount: 148_643, userId: 2));
        $this->assertFalse($validator->canUserPrepareTransaction(1, true, receivedEntityAmount: 148_644, userId: 2));

        # Check maximal limit for verified user and not inverted direction.

        $this->assertTrue($validator->canUserPrepareTransaction(1, false, 992_400, userId: 1));
        $this->assertFalse($validator->canUserPrepareTransaction(1, false, 992_441, userId: 1));

        $this->assertTrue($validator->canUserPrepareTransaction(1, false, receivedEntityAmount: 2270, userId: 1));
        $this->assertFalse($validator->canUserPrepareTransaction(1, false, receivedEntityAmount: 2271, userId: 1));

        # Check maximal limit for verified user and inverted direction.

        $this->assertTrue($validator->canUserPrepareTransaction(1, true, 2270, userId: 1));
        $this->assertFalse($validator->canUserPrepareTransaction(1, true, 2271, userId: 1));

        $this->assertTrue($validator->canUserPrepareTransaction(1, true, receivedEntityAmount: 992_400, userId: 1));
        $this->assertFalse($validator->canUserPrepareTransaction(1, true, receivedEntityAmount: 992_441, userId: 1));
    }
}
