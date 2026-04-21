<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class CohesiveClass
{
    private int $a = 0;

    private int $b = 0;

    private int $c = 0;

    public function one(): int
    {
        return $this->a + $this->b;
    }

    public function two(): int
    {
        return $this->b + $this->c;
    }

    public function three(): int
    {
        return $this->a + $this->c;
    }

    public function four(): int
    {
        return $this->one() + $this->two();
    }

    public function five(): int
    {
        return $this->three() + $this->four();
    }

    public function six(): int
    {
        return $this->a;
    }

    public function seven(): int
    {
        return $this->six();
    }
}
