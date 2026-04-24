<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class IgnoreNamesRespected
{
    private mixed $value = null;

    public function update(mixed $value): void
    {
        $this->value = $value;
    }
}
