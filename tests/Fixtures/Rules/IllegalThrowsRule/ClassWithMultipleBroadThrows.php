<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithMultipleBroadThrows
{
    /**
     * @throws \RuntimeException when something fails
     * @throws \Throwable on fatal error
     */
    public function run(): void {}
}
