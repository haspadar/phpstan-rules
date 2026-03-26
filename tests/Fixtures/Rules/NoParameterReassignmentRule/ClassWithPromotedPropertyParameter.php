<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithPromotedPropertyParameter
{
    public function __construct(private readonly string $name)
    {
    }
}
