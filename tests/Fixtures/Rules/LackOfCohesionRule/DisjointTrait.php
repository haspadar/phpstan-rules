<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

trait DisjointTrait
{
    private int $a = 0;

    private int $b = 0;

    public function firstGroupOne(): int
    {
        return $this->a;
    }

    public function firstGroupTwo(): int
    {
        return $this->a + 1;
    }

    public function secondGroupOne(): int
    {
        return $this->b;
    }

    public function secondGroupTwo(): int
    {
        return $this->b + 1;
    }
}
