<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SwitchDefaultRule;

final class DefaultNotLast
{
    public function run(string $status): void
    {
        switch ($status) {
            case 'active':
                $this->enable();
                break;
            default:
                throw new \RuntimeException('Unknown status');
            case 'inactive':
                $this->disable();
                break;
        }
    }

    private function enable(): void {}

    private function disable(): void {}
}
