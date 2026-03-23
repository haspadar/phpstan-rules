<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithCatchDependency
{
    public function run(): void
    {
        try {
            $a = new TypeA();
        } catch (TypeB $e) {
        }
    }
}
