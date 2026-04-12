<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

use Attribute;

#[Attribute]
final class ExampleAttribute
{
    public function __construct(
        public readonly string $value,
    ) {
    }
}

#[ExampleAttribute('/api/users')]
final class ClassWithAttributes
{
    #[ExampleAttribute('handler')]
    public function handle(): void
    {
    }
}
