<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SimplifyBooleanExpressionRule;

final class CompareWithFalse
{
    public function run(): void
    {
        if ($this->hasErrors() == false) {
            $this->save();
        }

        if ($this->hasErrors() === false) {
            $this->save();
        }
    }

    private function hasErrors(): bool
    {
        return false;
    }

    private function save(): void {}
}
