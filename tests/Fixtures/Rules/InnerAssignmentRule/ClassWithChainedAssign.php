<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class ClassWithChainedAssign
{
    public function init(): void
    {
        $a = $b = 0;

        echo $a + $b;
    }
}
