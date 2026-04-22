<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

final class ClassWithPrivateStaticProperty
{
    private static ?self $instance = null;
}
