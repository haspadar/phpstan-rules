<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithReassignmentInNestedFunction
{
    public function process(string $name): \Closure
    {
        function inner(string $name): string {
            $name = strtolower($name);

            return $name;
        }

        return static fn(): string => inner($name);
    }
}
