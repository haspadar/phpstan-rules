<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MissingThrowsRule;

abstract class ParentWithRunMethod
{
    public function run(): void
    {
    }
}
