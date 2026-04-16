<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

class ClassWithProtectedConst
{
    protected const string NAME = 'test';
}
