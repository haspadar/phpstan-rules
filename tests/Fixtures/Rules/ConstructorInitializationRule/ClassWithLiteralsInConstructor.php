<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithLiteralsInConstructor
{
    private const int DEFAULT_AGE = 18;

    private string $name;

    private int $age;

    private float $score;

    public function __construct()
    {
        $this->name = 'default';
        $this->age = self::DEFAULT_AGE;
        $this->score = 0.0;
    }
}
