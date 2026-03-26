<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalCatchRule;

final class ClassWithThrowableCatch
{
    public function run(): void
    {
        try {
            $this->doWork();
        } catch (\Throwable $e) {
            $this->handle($e);
        }
    }

    private function doWork(): void {}

    private function handle(\Throwable $e): void {}
}
