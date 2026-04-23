<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ForeignTargetForNullAssignment
{
    public string $cache = '';
}

final class ClassWithNullAssignmentToForeignProperty
{
    public function reset(ForeignTargetForNullAssignment $service): void
    {
        $service->cache = null;
    }
}
