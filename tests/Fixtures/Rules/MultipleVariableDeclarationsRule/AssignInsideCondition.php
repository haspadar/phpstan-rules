<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class AssignInsideCondition
{
    public function run(): int
    {
        $source = 5;

        if (($value = $source) > 0) {
            return $value;
        }

        return 0;
    }
}
