<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithReassignmentInClosure
{
    public function process(string $name): \Closure
    {
        return function () use ($name): string {
            $name = strtolower($name);

            return $name;
        };
    }
}
