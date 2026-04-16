<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

enum EnumWithConst: string
{
    case Active = 'active';

    public const string DEFAULT = 'active';
}
