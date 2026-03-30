<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingMethodRule;

use Override as Ovr;

class ClassWithAliasedOverrideAttribute
{
    #[Ovr]
    public function toString(): string
    {
        return 'value';
    }
}
