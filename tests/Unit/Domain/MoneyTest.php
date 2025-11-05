<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_it_creates_money_from_integer_value(): void
    {
        $money = new Money(500);

        $this->assertEquals(500, $money->getValue());
        $this->assertEquals('5.00', $money->asString());
    }

    public function test_it_creates_money_from_decimal_string(): void
    {
        $money = Money::fromDecimal('12.34');

        $this->assertEquals(1234, $money->getValue());
        $this->assertEquals('12.34', $money->asString());
    }

    public function test_it_creates_money_from_decimal_float(): void
    {
        $money = Money::fromDecimal(99.99);

        $this->assertEquals(9999, $money->getValue());
        $this->assertEquals('99.99', $money->asString());
    }

    public function test_from_decimal_throws_exception_for_invalid_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Money::fromDecimal('12.345');
    }

    public function test_cannot_create_negative_money(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Money(-1);
    }

    public function test_add_money(): void
    {
        $a = new Money(100);
        $b = new Money(200);

        $result = $a->add($b);

        $this->assertEquals(300, $result->getValue());
    }

    public function test_subtract_money(): void
    {
        $a = new Money(500);
        $b = new Money(300);

        $result = $a->subtract($b);

        $this->assertEquals(200, $result->getValue());
    }

    public function test_cannot_subtract_to_negative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Money(100))->subtract(new Money(200));
    }

    public function test_less_than(): void
    {
        $a = new Money(100);
        $b = new Money(200);

        $this->assertTrue($a->lessThan($b));
        $this->assertFalse($b->lessThan($a));
    }
}
