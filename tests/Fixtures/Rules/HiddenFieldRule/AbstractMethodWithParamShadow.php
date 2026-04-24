<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

abstract class AbstractMethodWithParamShadow
{
    protected string $name = '';

    abstract public function rename(string $name): void;
}
