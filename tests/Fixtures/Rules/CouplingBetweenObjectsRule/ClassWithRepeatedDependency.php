<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithRepeatedDependency
{
    public function run(TypeA $a): TypeA
    {
        return new TypeA();
    }
}
