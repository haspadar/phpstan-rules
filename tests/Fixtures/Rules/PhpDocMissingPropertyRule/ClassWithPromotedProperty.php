<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingPropertyRule;

final class ClassWithPromotedProperty
{
    public function __construct(public string $name)
    {
    }
}
