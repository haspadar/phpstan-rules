<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class ExternalReferences
{
    private int $a = 0;

    private int $b = 0;

    private int $c = 0;

    public function firstGroupOne(\stdClass $obj): int
    {
        $obj->name = 'x';

        return $this->a;
    }

    public function firstGroupTwo(\stdClass $obj): int
    {
        return $obj->value + $this->a;
    }

    public function firstGroupThree(): int
    {
        return \Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule\Helper::value() + $this->a;
    }

    public function firstGroupFour(): int
    {
        return $this->a - 1;
    }

    public function secondGroupOne(\stdClass $obj): int
    {
        $obj->foo();

        return $this->b;
    }

    public function secondGroupTwo(): int
    {
        return $this->b + $this->c;
    }

    public function secondGroupThree(): int
    {
        return $this->c;
    }
}

final class Helper
{
    public static function value(): int
    {
        return 1;
    }
}
