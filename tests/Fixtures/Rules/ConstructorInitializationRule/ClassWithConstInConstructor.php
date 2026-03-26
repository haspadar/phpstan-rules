<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithConstInConstructor
{
    private bool $active;

    private int $limit;

    public function __construct()
    {
        $this->active = true;
        $this->limit = PHP_INT_MAX;
    }
}
