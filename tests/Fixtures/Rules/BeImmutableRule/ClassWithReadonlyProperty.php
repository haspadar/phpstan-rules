<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BeImmutableRule;

final class ClassWithReadonlyProperty
{
    private readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
