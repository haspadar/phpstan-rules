<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

final class ClassWithPublicStaticProperty
{
    public static int $count = 0;
}
