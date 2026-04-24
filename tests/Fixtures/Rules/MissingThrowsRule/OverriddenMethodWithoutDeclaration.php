<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MissingThrowsRule;

use Override;

final class OverriddenMethodWithoutDeclaration extends ParentWithRunMethod
{
    #[Override]
    public function run(): void
    {
        throw new \RuntimeException();
    }
}
