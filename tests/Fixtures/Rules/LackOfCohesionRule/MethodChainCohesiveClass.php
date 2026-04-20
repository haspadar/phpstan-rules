<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class MethodChainCohesiveClass
{
    private int $a = 0;

    private int $b = 0;

    private int $c = 0;

    public function first(): int
    {
        return $this->second() + $this->a;
    }

    public function second(): int
    {
        return $this->third() + 1;
    }

    public function third(): int
    {
        return $this->fourth();
    }

    public function fourth(): int
    {
        return $this->b + $this->c;
    }

    public function fifth(): int
    {
        return $this->first() * 2;
    }

    public function sixth(): int
    {
        return $this->fifth() - 3;
    }

    public function seventh(): int
    {
        return $this->sixth();
    }
}
