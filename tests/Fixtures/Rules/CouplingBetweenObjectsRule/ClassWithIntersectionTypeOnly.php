<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithIntersectionTypeOnly
{
    public function process(TypeA&TypeB $intersection): void
    {
    }
}
