<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

final class ClassWithPublicConst
{
    public const string NAME = 'test';
}
