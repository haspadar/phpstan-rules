<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

abstract class AbstractDisjoint
{
    protected int $a = 0;

    protected int $b = 0;

    protected int $c = 0;

    public function first(): int
    {
        return $this->a;
    }

    public function second(): int
    {
        return $this->a + 1;
    }

    public function third(): int
    {
        return $this->a - 1;
    }

    public function fourth(): int
    {
        return $this->a * 2;
    }

    public function fifth(): int
    {
        return $this->b;
    }

    public function sixth(): int
    {
        return $this->b + $this->c;
    }

    public function seventh(): int
    {
        return $this->c;
    }
}
