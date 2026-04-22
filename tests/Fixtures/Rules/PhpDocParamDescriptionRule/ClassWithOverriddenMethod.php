<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamDescriptionRule;

use Override;

class ParentGreeterForDescriptionOverride
{
    /**
     * Greets a person.
     *
     * @param string $name The person to greet.
     */
    public function greet(string $name): string
    {
        return 'hello ' . $name;
    }
}

final class ClassWithOverriddenMethod extends ParentGreeterForDescriptionOverride
{
    /**
     * Greets loudly.
     *
     * @param string $name
     */
    #[Override]
    public function greet(string $name): string
    {
        return strtoupper($name);
    }
}
