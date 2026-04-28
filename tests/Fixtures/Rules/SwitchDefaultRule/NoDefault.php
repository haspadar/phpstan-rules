<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SwitchDefaultRule;

final class NoDefault
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
        }
    }

    private function enable(): void {}

    private function disable(): void {}
}
