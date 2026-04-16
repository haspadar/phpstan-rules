<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

interface InterfaceWithConst
{
    public const string NAME = 'test';
}
