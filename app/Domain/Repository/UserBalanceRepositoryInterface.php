<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\UserBalance;

interface UserBalanceRepositoryInterface
{
    public function find(int $userId): ?UserBalance;

    public function save(UserBalance $userBalance): void;
}
