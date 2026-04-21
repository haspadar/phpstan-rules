<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class FewMethods
{
    private int $a = 0;

    private int $b = 0;

    private int $c = 0;

    public function first(): int
    {
        return $this->a;
    }

    public function second(): int
    {
        return $this->b;
    }

    public function third(): int
    {
        return $this->c;
    }
}
