<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithBroadRuntimeExceptionThrows
{
    /**
     * @throws \RuntimeException when something fails
     */
    public function run(): void {}
}
