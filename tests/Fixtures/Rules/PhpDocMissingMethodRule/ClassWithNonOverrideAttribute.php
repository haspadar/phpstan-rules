<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingMethodRule;

class ClassWithNonOverrideAttribute
{
    #[\Deprecated('Use newMethod() instead')]
    public function oldMethod(): string
    {
        return 'old';
    }
}
