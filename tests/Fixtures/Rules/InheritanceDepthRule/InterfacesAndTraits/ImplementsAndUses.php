<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InheritanceDepthRule\InterfacesAndTraits;

final class ImplementsAndUses implements FirstContract, SecondContract
{
    use SharedTrait;

    public function first(): string
    {
        return 'first';
    }

    public function second(): string
    {
        return 'second';
    }
}
