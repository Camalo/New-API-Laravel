<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Application\UseCase\Deposit\DepositRequest;
use App\Application\UseCase\Deposit\DepositUseCase;
use Illuminate\Http\Request;

class DepositController
{
    public function __invoke(Request $request, DepositUseCase $useCase)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'amount'  => 'required|numeric|min:0.01',
            'comment' => 'nullable|string',
        ]);

        try {
            $response = $useCase(new DepositRequest(
                $validated['user_id'],
                $validated['amount'],
                $validated['comment']  ?? ''
            ));
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Неизвестная ошибка'
                ],
                500
            );
        }

        return response()->json([
            'user_id' => $response->userId,
            'amount' => $response->amount,
            'comment' => $response->comment
        ]);
    }
}
