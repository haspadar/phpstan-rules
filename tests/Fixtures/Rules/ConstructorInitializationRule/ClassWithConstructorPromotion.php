<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithConstructorPromotion
{
    public function __construct(
        private readonly string $name,
        private readonly int $age,
    ) {}
}
