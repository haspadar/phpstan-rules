<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class PropertyAssignment
{
    public string $value = '';

    public function run(): void
    {
        $this->value = 'test';
    }
}
