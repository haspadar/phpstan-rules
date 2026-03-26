<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithReassignmentInIf
{
    public function process(string $name): string
    {
        if (strlen($name) > 10) {
            $name = substr($name, 0, 10);
        }

        return $name;
    }
}
