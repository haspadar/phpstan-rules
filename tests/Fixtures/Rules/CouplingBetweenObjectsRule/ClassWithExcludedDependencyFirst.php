<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithExcludedDependencyFirst
{
    public function run(TypeA $excluded, TypeB $b, TypeC $c): void
    {
    }
}
