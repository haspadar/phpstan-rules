<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ThrowsCountRule;

final class ExactThrows
{
    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function run(): void {}
}
