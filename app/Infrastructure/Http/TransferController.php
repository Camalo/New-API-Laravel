<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Application\Exception\CannotWithdrawException;
use App\Application\Exception\UserBalanceNotFoundException;
use App\Application\UseCase\Transfer\TransferRequest;
use App\Application\UseCase\Transfer\TransferUseCase;
use Illuminate\Http\Request;

class TransferController
{
    public function __invoke(Request $request, TransferUseCase $useCase)
    {
        $request->headers->set('Accept', 'application/json');

        $validated = $request->validate([
            'from_user_id' => 'required|integer',
            'to_user_id'   => 'required|integer|different:from_user_id',
            'amount'       => 'required|numeric|min:0.01',
            'comment'      => 'nullable|string',
        ]);
       
        try {
            $response = $useCase(new TransferRequest(
                fromUserId: (int)$validated['from_user_id'],
                toUserId: (int)$validated['to_user_id'],
                amount: (float)$validated['amount'],
                comment: $validated['comment']  ?? ''
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
            'from_user_id' => $response->fromUserId,
            'to_user_id' => $response->toUserId,
            'amount' => $response->amount,
            'comment' => $response->comment
        ]);
    }
}
