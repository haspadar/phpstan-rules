<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoPhpDocForOverriddenRule;

/** A child class. */
final class ChildWithPhpDoc extends OverriddenMethodWithPhpDoc
{
    /**
     * Does something specific.
     */
    #[\Override]
    public function doSomething(): void {}
}
