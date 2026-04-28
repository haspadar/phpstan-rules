<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedSwitchRule;

final class NestedSwitch
{
    public function run(string $type, string $subType): string
    {
        switch ($type) {
            case 'foo':
                switch ($subType) {
                    case 'bar':
                        return 'foo-bar';
                    default:
                        return 'foo-other';
                }
            default:
                return 'other';
        }
    }
}
