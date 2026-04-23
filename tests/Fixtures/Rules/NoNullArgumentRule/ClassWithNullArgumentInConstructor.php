<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class ClassWithNullArgumentInConstructor
{
    public function run(): ConstructorTarget
    {
        return new ConstructorTarget(null);
    }
}
