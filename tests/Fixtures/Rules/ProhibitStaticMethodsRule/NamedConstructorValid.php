<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

final class NamedConstructorValid
{
    public function __construct(private readonly int $id)
    {
    }

    public static function fromId(int $id): self
    {
        return new self($id);
    }
}
