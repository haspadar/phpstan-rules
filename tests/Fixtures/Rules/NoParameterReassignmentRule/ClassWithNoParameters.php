<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithNoParameters
{
    public function greet(): string
    {
        return 'hello';
    }
}
