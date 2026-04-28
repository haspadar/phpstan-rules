<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SimplifyBooleanExpressionRule;

final class BooleanLiteralOnLeft
{
    public function run(): void
    {
        if (true === $this->isActive()) {
            $this->enable();
        }

        if (false == $this->hasErrors()) {
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
