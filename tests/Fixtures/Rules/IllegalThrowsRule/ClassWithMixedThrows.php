<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithMixedThrows
{
    /**
     * @throws DatabaseException when connection is lost
     * @throws \RuntimeException on unexpected error
     */
    public function run(): void {}
}
