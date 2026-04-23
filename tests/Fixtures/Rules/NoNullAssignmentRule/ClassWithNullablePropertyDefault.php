<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithNullablePropertyDefault
{
    private ?string $cache = null;

    public function value(): string
    {
        return $this->cache ?? '';
    }
}
