<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class FewProperties
{
    private int $a = 0;

    private int $b = 0;

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
        return $this->b + 1;
    }

    public function seventh(): int
    {
        return $this->b - 1;
    }
}
