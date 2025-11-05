<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Exception\CannotWithdrawException;
use App\Application\Exception\UserBalanceNotFoundException;
use App\Application\Factory\DepositTransactionFactory;
use App\Application\Factory\TransferInTransactionFactory;
use App\Application\Factory\TransferOutTransactionFactory;
use App\Application\Factory\WithdrawTransactionFactory;
use App\Domain\Repository\UserBalanceRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\ValueObject\Money;
use App\Domain\Entity\UserBalance;
use Illuminate\Support\Facades\DB;


class BalanceService
{
    public function __construct(
        private UserBalanceRepositoryInterface $balanceRepository,
        private TransactionRepositoryInterface $transactionRepository,
    ) {}

    public function deposit(int $userId, Money $amount, string $comment): void
    {
        DB::transaction(function () use ($userId, $amount, $comment) {

            $this->applyDeposit($userId, $amount);

            $this->transactionRepository->save(
                DepositTransactionFactory::create($userId, $amount, $comment)
            );
        });
    }

    public function withdraw(int $userId, Money $amount, string $comment): void
    {
        DB::transaction(function () use ($userId, $amount, $comment) {

            $this->applyWithdraw($userId, $amount);

            $this->transactionRepository->save(
                WithdrawTransactionFactory::create(
                    $userId,
                    $amount,
                    $comment
                )
            );
        });
    }

    public function transfer(int $fromUserId, int $toUserId, Money $amount, string $comment): void
    {
        DB::transaction(function () use ($fromUserId, $toUserId, $amount, $comment) {

            $this->applyWithdraw($fromUserId, $amount);

            $this->transactionRepository->save(
                TransferOutTransactionFactory::create(
                    $fromUserId,
                    $amount,
                    $comment
                )
            );

            $this->applyDeposit($toUserId, $amount);

            $this->transactionRepository->save(
                TransferInTransactionFactory::create(
                    $toUserId,
                    $amount,
                    $comment
                )
            );
        });
    }

    private function applyDeposit(int $userId, Money $amount)
    {
        $balance = $this->balanceRepository->find($userId);

        if ($balance === null) {
            $balance = new UserBalance(
                $userId,
                new Money(0)
            );
        }

        $balance->deposit($amount);

        $this->balanceRepository->save($balance);
    }

    private function applyWithdraw(int $userId, Money $amount)
    {
        $balance = $this->balanceRepository->find($userId);

        if ($balance === null) {
            throw new UserBalanceNotFoundException();
        }

        if ($balance->getBalance()->getValue() < $amount->getValue()) {
            throw new CannotWithdrawException();
        }
        $balance->withdraw($amount);

        $this->balanceRepository->save($balance);
    }
}
