<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoPhpDocForOverriddenRule;

/** A child class. */
final class ChildWithoutPhpDoc extends OverriddenMethodWithPhpDoc
{
    #[\Override]
    public function doSomething(): void {}
}
