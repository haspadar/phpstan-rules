<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BeImmutableRule;

final class ClassWithStaticProperty
{
    private static int $count = 0;
}
