<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class FewPropertiesClass
{
    private int $a = 0;

    private int $b = 0;

    public function one(): int
    {
        return $this->a;
    }

    public function two(): int
    {
        return $this->a + 1;
    }

    public function three(): int
    {
        return $this->a * 2;
    }

    public function four(): int
    {
        return $this->b;
    }

    public function five(): int
    {
        return $this->b + 1;
    }

    public function six(): int
    {
        return $this->b * 2;
    }

    public function seven(): int
    {
        return $this->b - 3;
    }
}
