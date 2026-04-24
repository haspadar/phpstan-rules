<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MissingThrowsRule;

final class ThrowsExceptionWithoutDeclaration
{
    public function run(): void
    {
        throw new \RuntimeException();
    }
}
