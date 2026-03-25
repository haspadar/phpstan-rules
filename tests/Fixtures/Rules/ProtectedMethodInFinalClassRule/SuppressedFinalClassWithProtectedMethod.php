<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProtectedMethodInFinalClassRule;

/** @phpstan-ignore haspadar.protectedInFinal */
final class SuppressedFinalClassWithProtectedMethod
{
    protected function query(): void
    {
    }
}
