<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithNullAssignmentToProperty
{
    private string $cache = '';

    public function reset(): void
    {
        $this->cache = null;
    }
}
