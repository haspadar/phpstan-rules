<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SimplifyBooleanExpressionRule;

final class ValidComparisons
{
    public function run(mixed $value, string $status): void
    {
        if ($value === null) {
            return;
        }

        if ($status === 'active') {
            $this->enable();
        }

        if ($this->isActive()) {
            $this->enable();
        }

        if (!$this->hasErrors()) {
            $this->save();
        }
    }

    private function isActive(): bool
    {
        return true;
    }

    private function hasErrors(): bool
    {
        return false;
    }

    private function enable(): void {}

    private function save(): void {}
}
