<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule;

final class MethodWithSwitch
{
    public function run(int $x): string
    {
        switch ($x) {
            case 1:
                return 'one';
            case 2:
                return 'two';
            default:
                return 'other';
        }
    }
}
