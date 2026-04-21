<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class ExternalStaticProperties extends StaticPropertiesParent
{
    private int $a = 0;

    private int $b = 0;

    public function firstGroupOne(): int
    {
        return $this->a + StaticPropertiesHolder::$shared;
    }

    public function firstGroupTwo(): int
    {
        return $this->a + parent::$inherited;
    }

    public function firstGroupThree(): int
    {
        return $this->a;
    }

    public function secondGroupOne(): int
    {
        return $this->b + StaticPropertiesHolder::$shared;
    }

    public function secondGroupTwo(): int
    {
        return $this->b - parent::$inherited;
    }

    public function secondGroupThree(): int
    {
        return $this->b;
    }
}

class StaticPropertiesParent
{
    public static int $inherited = 0;
}

final class StaticPropertiesHolder
{
    public static int $shared = 0;
}
