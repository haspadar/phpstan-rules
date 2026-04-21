<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class StaticReferences
{
    private static int $a = 0;

    private static int $b = 0;

    private static int $c = 0;

    public function firstGroupOne(): int
    {
        return self::$a;
    }

    public function firstGroupTwo(): int
    {
        return self::$a + 1;
    }

    public function firstGroupThree(): int
    {
        return static::$a * 2;
    }

    public function firstGroupFour(): int
    {
        return self::firstGroupOne();
    }

    public function secondGroupOne(): int
    {
        return self::$b + self::$c;
    }

    public function secondGroupTwo(): int
    {
        return static::secondGroupOne();
    }

    public function secondGroupThree(): int
    {
        return self::$c;
    }
}
