<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Entity\UserBalance;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class userBalanceTest  extends TestCase
{
    public function test_it_creates_user_balance(): void
    {
        $balance = new UserBalance(1, new Money(1000));

        $this->assertEquals(1, $balance->getUserId());
        $this->assertEquals(1000, $balance->getBalance()->getValue());
    }

    public function test_it_deposits_money(): void
    {
        $balance = new UserBalance(1, new Money(1000));

        $balance->deposit(new Money(500));

        $this->assertEquals(1500, $balance->getBalance()->getValue());
    }

    public function test_it_withdraws_money(): void
    {
        $balance = new UserBalance(1, new Money(1000));

        $balance->withdraw(new Money(400));

        $this->assertEquals(600, $balance->getBalance()->getValue());
    }

    public function test_it_cannot_withdraw_more_than_balance(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $balance = new UserBalance(1, new Money(300));
        $balance->withdraw(new Money(500));
    }
}
