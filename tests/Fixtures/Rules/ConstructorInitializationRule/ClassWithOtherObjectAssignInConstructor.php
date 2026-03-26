<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithOtherObjectAssignInConstructor
{
    private string $name;

    public function __construct(string $name, \stdClass $other)
    {
        $other->name = $name;
        $this->name = $name;
    }
}
