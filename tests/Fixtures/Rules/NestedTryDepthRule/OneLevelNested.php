<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule;

final class OneLevelNested
{
    public function run(): void
    {
        try {
            try {
                $this->doRisky();
            } catch (\RuntimeException $e) {
                throw new \DomainException('inner', 0, $e);
            }
        } catch (\DomainException $e) {
            $this->log($e->getMessage());
        }
    }

    private function doRisky(): void {}

    private function log(string $message): void {}
}
