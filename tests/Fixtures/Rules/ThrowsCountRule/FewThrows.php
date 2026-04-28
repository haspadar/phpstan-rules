<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ThrowsCountRule;

final class FewThrows
{
    /**
     * @throws \InvalidArgumentException
     */
    public function run(): void {}
}
