<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingPropertyRule;

final class SuppressedProperty
{
    // @phpstan-ignore haspadar.phpdocMissingProperty
    public string $name = '';
}
