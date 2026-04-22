<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

final class ClassWithGroupedStaticProperties
{
    public static int $first = 0, $second = 0;
}
