<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule;

final class TryInsideClosure
{
    public function build(): callable
    {
        try {
            return function (): void {
                try {
                    try {
                        $this->doRisky();
                    } catch (\RuntimeException $e) {
                        throw new \DomainException('inner', 0, $e);
                    }
                } catch (\DomainException $e) {
                    $this->log($e->getMessage());
                }
            };
        } catch (\Throwable $e) {
            return static fn (): null => null;
        }
    }

    private function doRisky(): void {}

    private function log(string $message): void {}
}
