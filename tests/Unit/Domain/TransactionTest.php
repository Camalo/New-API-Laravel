<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Application\Factory\DepositTransactionFactory;
use App\Application\Factory\TransferInTransactionFactory;
use App\Application\Factory\TransferOutTransactionFactory;
use App\Application\Factory\WithdrawTransactionFactory;
use App\Domain\Entity\Transaction;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function test_it_creates_deposit_transaction()
    {
        $money = new Money(1000);

        $transaction = DepositTransactionFactory::create(1, $money, 'Test deposit');

        $this->assertEquals('deposit', $transaction->getType());
    }

    public function test_it_creates_withdraw_transaction()
    {
        $money = new Money(2000);

        $transaction = WithdrawTransactionFactory::create(5, $money, 'ATM');

        $this->assertEquals('withdraw', $transaction->getType());
    }

    public function test_it_creates_transfer_in_transaction()
    {
        $money = new Money(3000);

        $transaction = TransferInTransactionFactory::create(1, $money, 'Test deposit');

        $this->assertEquals('transfer_in', $transaction->getType());
    }

    public function test_it_creates_transfer_transaction()
    {
        $money = new Money(4000);

        $transaction = TransferOutTransactionFactory::create(5, $money, 'ATM');

        $this->assertEquals('transfer_out', $transaction->getType());
    }

    public function test_it_throws_exception_on_invalid_type()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Transaction(
            userId: 1,
            type: 'invalid',
            amount: new Money(100)
        );
    }

    public function test_it_sets_created_at_automatically()
    {
        $transaction = DepositTransactionFactory::create(1, new Money(500));

        $this->assertInstanceOf(\DateTimeImmutable::class, $transaction->getCreatedAt());
    }
}
