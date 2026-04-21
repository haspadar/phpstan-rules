<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitPublicStaticMethodsRule;

final class ClassWithPublicMethod
{
    public function create(): self
    {
        return new self();
    }
}
