<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class ClassWithNullArgumentInMethodCall
{
    public function run(MethodCallTarget $target): string
    {
        return $target->accept(null);
    }
}
