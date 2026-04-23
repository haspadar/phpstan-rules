<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithMultipleNullAssignments
{
    /** @var array<string, mixed> */
    private array $data = [];

    public function reset(): void
    {
        $first = null;
        $this->data = null;
        $this->data['key'] = null;
    }
}
