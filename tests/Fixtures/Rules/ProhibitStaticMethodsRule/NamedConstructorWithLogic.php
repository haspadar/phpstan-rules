<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

final class NamedConstructorWithLogic
{
    public function __construct(private readonly int $id)
    {
    }

    public static function fromArray(array $row): self
    {
        $id = (int) $row['id'];

        return new self($id);
    }
}
