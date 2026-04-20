<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class SelfStaticCallCohesiveClass
{
    private static int $counter = 0;

    private int $a = 0;

    private int $b = 0;

    public function first(): int
    {
        return self::second() + $this->a;
    }

    public static function second(): int
    {
        return static::third() + self::$counter;
    }

    public static function third(): int
    {
        return self::$counter;
    }

    public function fourth(): int
    {
        return $this->a + $this->b;
    }

    public function fifth(): int
    {
        return $this->first();
    }

    public function sixth(): int
    {
        return $this->fourth();
    }

    public function seventh(): int
    {
        return $this->fifth() + $this->sixth();
    }
}
