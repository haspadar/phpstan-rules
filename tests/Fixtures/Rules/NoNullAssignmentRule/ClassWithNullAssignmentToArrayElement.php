<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithNullAssignmentToArrayElement
{
    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        $result = [];
        $result['cached'] = null;
        return $result;
    }
}
