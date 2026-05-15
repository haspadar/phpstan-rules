<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

class NamedConstructorValidStatic
{
    public function __construct(protected readonly int $id)
    {
    }

    public static function fromId(int $id): static
    {
        return new static($id);
    }
}
