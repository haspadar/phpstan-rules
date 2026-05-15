<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

final class NamedConstructorWithIfElse
{
    public function __construct(private readonly int $id)
    {
    }

    public static function fromMixed(int|string $id): self
    {
        if (is_int($id)) {
            return new self($id);
        }

        return new self((int) $id);
    }
}
