<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class ConstructorTarget
{
    public function __construct(public ?string $value)
    {
    }
}
