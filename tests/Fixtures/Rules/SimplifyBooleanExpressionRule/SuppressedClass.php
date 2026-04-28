<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SimplifyBooleanExpressionRule;

final class SuppressedClass
{
    public function run(): void
    {
        /** @phpstan-ignore haspadar.simplifyBoolean */
        if ($this->isActive() == true) {
            $this->enable();
        }
    }

    private function isActive(): bool
    {
        return true;
    }

    private function enable(): void {}
}
