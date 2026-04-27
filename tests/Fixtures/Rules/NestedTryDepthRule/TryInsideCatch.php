<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule;

final class TryInsideCatch
{
    public function run(): void
    {
        try {
            $this->doRisky();
        } catch (\RuntimeException $e) {
            try {
                $this->fallback();
            } catch (\LogicException $inner) {
                $this->log($inner->getMessage());
            }
        }
    }

    private function doRisky(): void {}

    private function fallback(): void {}

    private function log(string $message): void {}
}
