<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Application\Exception\UserBalanceNotFoundException;
use App\Application\UseCase\Balance\BalanceUseCase;

class BalanceController
{
    public function __invoke(int $user_id, BalanceUseCase $useCase)
    {
        if ($user_id <= 0) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Невалидное значение user_id'
                ],
                400
            );
        }
        try {
            $response = $useCase($user_id);
        } catch (UserBalanceNotFoundException $e) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                404
            );
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
            'balance' => $response->balance
        ]);
    }
}
