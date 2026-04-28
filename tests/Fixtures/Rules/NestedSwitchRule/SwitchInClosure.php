<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedSwitchRule;

final class SwitchInClosure
{
    public function run(string $type): \Closure
    {
        switch ($type) {
            case 'foo':
                return static function (string $subType): string {
                    switch ($subType) {
                        case 'bar':
                            return 'foo-bar';
                        default:
                            return 'foo-other';
                    }
                };
            default:
                return static fn(): string => 'other';
        }
    }
}
