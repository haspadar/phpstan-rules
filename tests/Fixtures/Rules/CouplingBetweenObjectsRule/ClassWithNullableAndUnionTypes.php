<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithNullableAndUnionTypes
{
    private ?TypeA $nullable;

    public function process(TypeB|TypeC $union, TypeD&TypeE $intersection): void
    {
    }
}
