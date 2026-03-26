<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalCatchRule;

final class ClassWithCleanCatch
{
    public function run(): void
    {
        try {
            $this->doWork();
        } catch (DatabaseException $e) {
            $this->handle($e);
        }
    }

    private function doWork(): void {}

    private function handle(DatabaseException $e): void {}
}
