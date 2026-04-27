<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SwitchDefaultRule;

final class WithDefault
{
    public function run(string $status): void
    {
        switch ($status) {
            case 'active':
                $this->enable();
                break;
            case 'inactive':
                $this->disable();
                break;
            default:
                throw new \RuntimeException('Unknown status');
        }
    }

    private function enable(): void {}

    private function disable(): void {}
}
