<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

final class ClassWithNullableReadonlyProperty
{
    public readonly ?int $age;

    public function __construct()
    {
        $this->age = 0;
    }
}
