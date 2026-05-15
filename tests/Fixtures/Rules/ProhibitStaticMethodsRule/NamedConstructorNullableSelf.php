<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

final class NamedConstructorNullableSelf
{
    public function __construct(private readonly int $id)
    {
    }

    public static function fromId(int $id): ?self
    {
        $cached = null;

        return $cached ?? new self($id);
    }
}
