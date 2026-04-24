<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class ConstructorWithShadowOptOut
{
    private string $name = '';

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
