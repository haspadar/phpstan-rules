<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class ParameterShadowsProperty
{
    private string $name = '';

    public function rename(string $name): void
    {
        $this->name = $name;
    }
}
