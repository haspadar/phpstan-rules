<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithSelfCallInConstructor
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
        self::validate($name);
    }

    /** @param string $name */
    private static function validate(string $name): void {}
}
