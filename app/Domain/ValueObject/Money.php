<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class Money
{
    private int $value;

    public function __construct(int $value)
    {
        $this->assert($value);

        $this->value = $value;
    }

    public static function fromDecimal(float|string $decimal): self
    {
        $decimal = trim((string)$decimal);

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $decimal)) {
            throw new \InvalidArgumentException("Invalid decimal money value: $decimal");
        }

        $value = (int)round($decimal * 100);

        return new self($value);
    }
    
    public function getValue(): int
    {
        return $this->value;
    }

    public function asString(): string
    {
        return number_format($this->value / 100, 2, '.', '');
    }

    public function add(self $other): self
    {
        return new self($this->value + $other->value);
    }

    public function subtract(self $other): self
    {
        $newValue = $this->value - $other->value;

        if ($newValue < 0) {
            throw new \InvalidArgumentException('Balance cannot go negative.');
        }

        return new self($newValue);
    }

    public function lessThan(self $other): bool
    {
        return $this->value < $other->value;
    }

    private function assert(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Amount cannot go negative.');
        }
    }
}
