<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

interface FirstInterface
{
}

interface SecondInterface
{
}

final class ClassWithIntersectionTypeProperty
{
    public FirstInterface&SecondInterface $dependency;

    public function __construct(FirstInterface&SecondInterface $dependency)
    {
        $this->dependency = $dependency;
    }
}
