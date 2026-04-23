<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class ClassWithNullsafeInCompoundExpression
{
    public function run(?NullsafeMethodCallTarget $target): string
    {
        return $target?->accept(null) ?? 'fallback';
    }
}
