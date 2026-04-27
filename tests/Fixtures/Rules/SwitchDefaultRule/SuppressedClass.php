<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SwitchDefaultRule;

final class SuppressedClass
{
    public function run(string $status): void
    {
        /** @phpstan-ignore haspadar.switchDefault */
        switch ($status) {
            case 'active':
                $this->enable();
                break;
        }
    }

    private function enable(): void {}
}
