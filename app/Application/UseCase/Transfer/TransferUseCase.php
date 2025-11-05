<?php

declare(strict_types=1);

namespace App\Application\UseCase\Transfer;

use App\Application\Service\BalanceService;
use App\Domain\ValueObject\Money;

class TransferUseCase
{
    public function __construct(
        private BalanceService $balanceService
    ) {}

    public function __invoke(TransferRequest $request): TransferResponse
    {
        $amount = Money::fromDecimal($request->amount);

        $this->balanceService->transfer(
            $request->fromUserId,
            $request->toUserId,
            $amount,
            $request->comment
        );

        // TODO rename methods in Money
        return new TransferResponse(
            $request->fromUserId,
            $request->toUserId,
            $amount->asString(),
            $request->comment
        );
    }
}
