<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProtectedMethodInFinalClassRule;

final class FinalClassWithProtectedMethod
{
    protected function query(): void
    {
    }
}
