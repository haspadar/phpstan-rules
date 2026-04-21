<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class PromotedProperties
{
    public function __construct(
        private readonly int $a,
        private readonly int $b,
        private readonly int $c,
    ) {
    }

    public function firstGroupOne(): int
    {
        return $this->a;
    }

    public function firstGroupTwo(): int
    {
        return $this->a + 1;
    }

    public function firstGroupThree(): int
    {
        return $this->a - 1;
    }

    public function firstGroupFour(): int
    {
        return $this->a * 2;
    }

    public function secondGroupOne(): int
    {
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
