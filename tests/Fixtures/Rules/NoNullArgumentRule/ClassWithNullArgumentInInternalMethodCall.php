<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

use ArrayObject;

final class ClassWithNullArgumentInInternalMethodCall
{
    public function run(): void
    {
        $container = new ArrayObject(['x']);
        $container->offsetSet(null, 'v');
    }
}
