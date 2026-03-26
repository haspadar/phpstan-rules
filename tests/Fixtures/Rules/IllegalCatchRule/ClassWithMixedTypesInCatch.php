<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalCatchRule;

final class ClassWithMixedTypesInCatch
{
    public function run(): void
    {
        try {
            $this->doWork();
        } catch (DatabaseException|\Exception $e) {
            $this->handle($e);
        }
    }

    private function doWork(): void {}

    private function handle(\Exception $e): void {}
}
