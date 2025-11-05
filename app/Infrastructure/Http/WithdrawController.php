<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Application\Exception\CannotWithdrawException;
use App\Application\Exception\UserBalanceNotFoundException;
use App\Application\UseCase\Withdraw\WithdrawRequest;
use App\Application\UseCase\Withdraw\WithdrawUseCase;
use Illuminate\Http\Request;

class WithdrawController
{
    public function __invoke(Request $request, WithdrawUseCase $useCase)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'amount'  => 'required|numeric|min:0.01',
            'comment' => 'nullable|string',
        ]);

        try {
            $response = $useCase(new WithdrawRequest(
                $validated['user_id'],
                $validated['amount'],
                $validated['comment']  ?? ''
            ));
        } catch (CannotWithdrawException $e) {
            
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                409
            );
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
            'amount' => $response->amount,
            'comment' => $response->comment
        ]);
    }
}
