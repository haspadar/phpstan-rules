<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoPhpDocForOverriddenRule;

/** A child class with suppressed rule. */
final class SuppressedOverriddenMethod extends OverriddenMethodWithPhpDoc
{
    /** Does something specific. */
    // @phpstan-ignore haspadar.noPhpdocOverride
    #[\Override]
    public function doSomething(): void {}
}
