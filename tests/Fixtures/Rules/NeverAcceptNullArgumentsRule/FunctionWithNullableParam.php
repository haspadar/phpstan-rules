<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule;

function greetNullable(?string $name): string
{
    return $name ?? 'world';
}
