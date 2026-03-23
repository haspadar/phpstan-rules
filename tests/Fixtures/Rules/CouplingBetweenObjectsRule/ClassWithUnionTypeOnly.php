<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithUnionTypeOnly
{
    public function process(TypeA|TypeB $union): void
    {
    }
}
