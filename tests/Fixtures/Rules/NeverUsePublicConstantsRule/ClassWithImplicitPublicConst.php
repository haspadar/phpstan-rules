<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

final class ClassWithImplicitPublicConst
{
    const string NAME = 'test';
}
