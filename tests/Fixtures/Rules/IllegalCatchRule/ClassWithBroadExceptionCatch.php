<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalCatchRule;

final class ClassWithBroadExceptionCatch
{
    public function run(): void
    {
        try {
            $this->doWork();
        } catch (\Exception $e) {
            $this->handle($e);
        }
    }

    private function doWork(): void {}

    private function handle(\Exception $e): void {}
}
