<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class ClassWithAssignInFunctionArg
{
    public function process(string $input): int
    {
        return strlen($trimmed = trim($input));
    }
}
