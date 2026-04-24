<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MissingThrowsRule;

final class ThrowsExceptionWithDeclaration
{
    /**
     * @throws \RuntimeException
     */
    public function run(): void
    {
        throw new \RuntimeException();
    }
}
