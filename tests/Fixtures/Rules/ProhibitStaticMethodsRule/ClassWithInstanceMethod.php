<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

final class ClassWithInstanceMethod
{
    public function create(): self
    {
        return new self();
    }
}
