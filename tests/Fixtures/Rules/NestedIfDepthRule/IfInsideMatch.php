<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule;

final class IfInsideMatch
{
    public function run(int $value, int $extra): int
    {
        return match (true) {
            $value > 100 => $value,
            default => $this->resolve($extra),
        };
    }

    private function resolve(int $extra): int
    {
        if ($extra > 0) {
            if ($extra < 50) {
                return $extra;
            }
        }

        return 0;
    }
}
