<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoPhpDocForOverriddenRule;

/** A base class. */
abstract class OverriddenMethodWithPhpDoc
{
    /** Does something. */
    abstract public function doSomething(): void;
}
