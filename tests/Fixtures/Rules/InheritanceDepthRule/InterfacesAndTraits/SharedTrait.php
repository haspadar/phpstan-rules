<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InheritanceDepthRule\InterfacesAndTraits;

trait SharedTrait
{
    public function shared(): string
    {
        return 'shared';
    }
}
