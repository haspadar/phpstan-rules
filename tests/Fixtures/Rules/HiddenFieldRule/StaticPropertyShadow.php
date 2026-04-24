<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class StaticPropertyShadow
{
    private static int $counter = 0;

    public function increment(int $counter): void
    {
        self::$counter += $counter;
    }
}
