<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithNewInConstructor
{
    private \stdClass $data;

    public function __construct()
    {
        $this->data = new \stdClass();
    }
}
