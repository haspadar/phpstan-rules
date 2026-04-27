<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule;

final class SiblingTriesWithNested
{
    public function run(): void
    {
        try {
            try {
                $this->first();
            } catch (\RuntimeException $e) {
                $this->log($e->getMessage());
            }
        } catch (\Throwable $e) {
            $this->log($e->getMessage());
        }
        try {
            try {
                $this->second();
            } catch (\RuntimeException $e) {
                $this->log($e->getMessage());
            }
        } catch (\Throwable $e) {
            $this->log($e->getMessage());
        }
    }

    private function first(): void {}

    private function second(): void {}

    private function log(string $message): void {}
}
