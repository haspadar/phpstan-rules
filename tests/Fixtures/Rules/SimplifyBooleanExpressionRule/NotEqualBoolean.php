<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SimplifyBooleanExpressionRule;

final class NotEqualBoolean
{
    public function run(): void
    {
        if ($this->isActive() != true) {
            $this->disable();
        }

        if ($this->isActive() !== false) {
            $this->enable();
        }
    }

    private function isActive(): bool
    {
        return true;
    }

    private function enable(): void {}

    private function disable(): void {}
}
