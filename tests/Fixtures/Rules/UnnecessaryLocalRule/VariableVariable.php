<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class VariableVariable
{
    /**
     * @param non-empty-string $name
     */
    public function run(string $name): mixed
    {
        $$name = 42;
        return $$name;
    }
}
