<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AbbreviationAsWordInNameRule;

final class PromotedProperty
{
    public function __construct(
        private readonly string $HTTPSConnection,
    ) {
    }
}
