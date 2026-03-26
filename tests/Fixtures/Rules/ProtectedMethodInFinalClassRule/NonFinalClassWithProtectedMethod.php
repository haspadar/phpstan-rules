<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProtectedMethodInFinalClassRule;

class NonFinalClassWithProtectedMethod
{
    protected function query(): void
    {
    }
}
