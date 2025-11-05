<?php

declare(strict_types=1);

namespace App\Application\Exception;

class CannotWithdrawException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Недостаточно средств для списания');
    }
}