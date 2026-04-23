<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

final class ClassWithPromotedNullableProperty
{
    public function __construct(
        public ?string $name = '',
    ) {
    }
}
