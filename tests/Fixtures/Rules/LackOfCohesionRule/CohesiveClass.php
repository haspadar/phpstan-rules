<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class CohesiveClass
{
    private int $a = 0;

    private int $b = 0;

    private int $c = 0;

    public function first(): int
    {
        return $this->a + $this->b;
    }

    public function second(): int
    {
        return $this->b + $this->c;
    }

    public function third(): int
    {
        return $this->a + $this->c;
    }

    public function fourth(): int
    {
        return $this->a;
    }

    public function fifth(): int
    {
        return $this->b;
    }

    public function sixth(): int
    {
        return $this->c;
    }

    public function seventh(): int
    {
        return $this->a + $this->b + $this->c;
    }
}
