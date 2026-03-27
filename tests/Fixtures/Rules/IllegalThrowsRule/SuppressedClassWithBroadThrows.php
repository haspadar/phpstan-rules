<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class SuppressedClassWithBroadThrows
{
    /**
     * @phpstan-ignore-next-line haspadar.illegalThrows
     * @throws \RuntimeException when something fails
     */
    public function run(): void {}
}
