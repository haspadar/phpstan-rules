<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class MixedCaseCallCohesiveClass
{
    private int $a = 0;

    private int $b = 0;

    private int $c = 0;

    public function doWork(): int
    {
        return $this->DoHelper() + $this->a;
    }

    public function doHelper(): int
    {
        return $this->b;
    }

    public function firstGroupExtra(): int
    {
        return $this->a;
    }

    public function secondGroupOne(): int
    {
        return $this->b;
    }

    public function secondGroupTwo(): int
    {
        return $this->c;
    }

    public function secondGroupThree(): int
    {
        return $this->b + $this->c;
    }

    public function secondGroupFour(): int
    {
        return $this->c * 2;
    }
}
