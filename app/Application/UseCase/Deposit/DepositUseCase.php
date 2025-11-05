<?php

declare(strict_types=1);

namespace App\Application\UseCase\Deposit;

use App\Application\Service\BalanceService;
use App\Domain\ValueObject\Money;

class DepositUseCase
{
    public function __construct(
        private BalanceService $balanceService
    ) {}

    public function __invoke(DepositRequest $request): DepositResponse
    {

        $amount = Money::fromDecimal($request->amount);

        $this->balanceService->deposit(
            $request->userId,
            $amount,
            $request->comment
        );

        // TODO rename methods in Money
        return new DepositResponse(
            $request->userId,
            $amount->asString(),
            $request->comment
        );
    }
}
