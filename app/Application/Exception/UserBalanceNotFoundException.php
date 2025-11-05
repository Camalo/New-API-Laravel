<?php

declare(strict_types=1);

namespace App\Application\Exception;

class UserBalanceNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Баланс пользователя не найден.');
    }
}