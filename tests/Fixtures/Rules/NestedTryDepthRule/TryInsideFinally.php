<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule;

final class TryInsideFinally
{
    public function run(): void
    {
        try {
            $this->doRisky();
        } finally {
            try {
                $this->release();
            } catch (\RuntimeException $e) {
                $this->log($e->getMessage());
            }
        }
    }

    private function doRisky(): void {}

    private function release(): void {}

    private function log(string $message): void {}
}
