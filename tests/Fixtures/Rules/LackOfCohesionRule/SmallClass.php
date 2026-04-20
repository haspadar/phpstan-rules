<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class SmallClass
{
    private int $a = 0;

    private int $b = 0;

    private int $c = 0;

    public function one(): int
    {
        return $this->a;
    }

    public function two(): int
    {
        return $this->b;
    }

    public function three(): int
    {
        return $this->c;
    }

    public function four(): int
    {
        return $this->a + $this->b;
    }
}
