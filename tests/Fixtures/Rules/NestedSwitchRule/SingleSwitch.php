<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedSwitchRule;

final class SingleSwitch
{
    public function run(string $type): string
    {
        switch ($type) {
            case 'foo':
                return 'foo';
            default:
                return 'other';
        }
    }
}
