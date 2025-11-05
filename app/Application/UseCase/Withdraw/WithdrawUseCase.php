<?php

declare(strict_types=1);

namespace App\Application\UseCase\Withdraw;

use App\Application\Service\BalanceService;
use App\Domain\ValueObject\Money;

class WithdrawUseCase
{
    public function __construct(
        private BalanceService $balanceService
    ) {}

    public function __invoke(WithdrawRequest $request): WithdrawResponse
    {
        $amount = Money::fromDecimal($request->amount);

        $this->balanceService->withdraw(
            $request->userId,
            $amount,
            $request->comment
        );

        
        return new WithdrawResponse(
            $request->userId,
            $amount->asString(),
            $request->comment
        );
    }
}
