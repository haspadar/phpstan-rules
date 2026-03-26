<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithReassignmentInAnonymousClass
{
    public function process(string $name): object
    {
        return new class ($name) {
            public function __construct(private string $name)
            {
                $this->name = strtolower($name);
            }
        };
    }
}
