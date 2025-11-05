<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\Exception\CannotWithdrawException;
use App\Application\Exception\UserBalanceNotFoundException;
use App\Application\Service\BalanceService;
use App\Domain\Entity\UserBalance;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Repository\UserBalanceRepositoryInterface;
use App\Domain\ValueObject\Money;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class BalanceServiceTest extends TestCase{
     private $balanceRepository;
    private $transactionRepository;
    private BalanceService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->balanceRepository = Mockery::mock(UserBalanceRepositoryInterface::class);
        $this->transactionRepository = Mockery::mock(TransactionRepositoryInterface::class);

        // Перехватываем DB::transaction, чтобы просто выполнить callback
        DB::shouldReceive('transaction')->andReturnUsing(function ($callback) {
            return $callback();
        });

        $this->service = new BalanceService(
            $this->balanceRepository,
            $this->transactionRepository
        );
    }

    public function test_deposit_creates_balance_if_not_exists()
    {
        $userId = 1;
        $amount = Money::fromDecimal(100);

        $this->balanceRepository->shouldReceive('find')
            ->once()->with($userId)->andReturn(null);

        $this->balanceRepository->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (UserBalance $balance) use ($userId, $amount) {
                return $balance->getUserId() === $userId &&
                    $balance->getBalance()->getValue() === $amount->getValue();
            }));

        $this->transactionRepository->shouldReceive('save')->once();

        $this->service->deposit($userId, $amount, 'test');
        $this->assertTrue(true);
    }

    public function test_withdraw_throws_if_balance_not_found()
    {
        $this->expectException(UserBalanceNotFoundException::class);

        $this->balanceRepository->shouldReceive('find')
            ->once()->andReturn(null);

        $this->service->withdraw(1, Money::fromDecimal(10), 'test');
    }

    public function test_withdraw_throws_if_insufficient_funds()
    {
        $balance = new UserBalance(1, Money::fromDecimal(5));

        $this->balanceRepository->shouldReceive('find')
            ->once()->andReturn($balance);

        $this->expectException(CannotWithdrawException::class);

        $this->service->withdraw(1, Money::fromDecimal(10), 'test');
    }

    public function test_withdraw_updates_balance_and_logs_transaction()
    {
        $balance = new UserBalance(1, Money::fromDecimal(100));

        $this->balanceRepository->shouldReceive('find')
            ->once()->andReturn($balance);

        $this->balanceRepository->shouldReceive('save')
            ->once()->with(Mockery::on(function (UserBalance $b) {
                return $b->getBalance()->getValue() === Money::fromDecimal(50)->getValue();
            }));

        $this->transactionRepository->shouldReceive('save')->once();

        $this->service->withdraw(1, Money::fromDecimal(50), 'test');

        $this->assertTrue(true);
    }

    public function test_transfer_updates_two_balances_and_logs_two_transactions()
    {
        $from = new UserBalance(1, Money::fromDecimal(200));
        $to   = new UserBalance(2, Money::fromDecimal(50));

        $this->balanceRepository->shouldReceive('find')->with(1)->andReturn($from);
        $this->balanceRepository->shouldReceive('find')->with(2)->andReturn($to);

        $this->balanceRepository->shouldReceive('save')->twice();
        $this->transactionRepository->shouldReceive('save')->twice();

        $this->service->transfer(1, 2, Money::fromDecimal(100), 'send money');

        $this->assertEquals(10000, $from->getBalance()->getValue());
        $this->assertEquals(15000, $to->getBalance()->getValue());
    }
}