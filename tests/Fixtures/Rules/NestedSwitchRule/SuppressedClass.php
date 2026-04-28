<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedSwitchRule;

final class SuppressedClass
{
    public function run(string $type, string $subType): string
    {
        switch ($type) {
            case 'foo':
                /** @phpstan-ignore haspadar.nestedSwitch */
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
