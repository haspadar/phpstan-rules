<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ThrowsCountRule;

final class TooManyThrows
{
    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function run(): void {}
}
