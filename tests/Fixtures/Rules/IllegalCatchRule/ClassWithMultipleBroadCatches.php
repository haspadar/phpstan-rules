<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalCatchRule;

final class ClassWithMultipleBroadCatches
{
    public function run(): void
    {
        try {
            $this->first();
        } catch (\RuntimeException $e) {
            // first broad catch
        }

        try {
            $this->second();
        } catch (\Error $e) {
            // second broad catch
        }
    }

    private function first(): void {}

    private function second(): void {}
}
