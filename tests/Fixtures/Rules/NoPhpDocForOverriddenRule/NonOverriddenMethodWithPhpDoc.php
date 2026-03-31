<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoPhpDocForOverriddenRule;

/** A standalone class. */
final class NonOverriddenMethodWithPhpDoc
{
    /** Does something. */
    public function doSomething(): void {}
}
