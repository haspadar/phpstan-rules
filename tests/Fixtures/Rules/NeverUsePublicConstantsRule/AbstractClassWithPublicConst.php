<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

abstract class AbstractClassWithPublicConst
{
    public const string NAME = 'test';
}
