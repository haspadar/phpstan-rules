<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule;

final class ShallowTry
{
    public function run(): void
    {
        try {
            $this->doRisky();
        } catch (\RuntimeException $e) {
            $this->log($e->getMessage());
        }
    }

    private function doRisky(): void {}

    private function log(string $message): void {}
}
