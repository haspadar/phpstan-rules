<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

final class ClassWithMixedVisibilityConsts
{
    public const string PUBLIC_ONE = 'a';
    private const string PRIVATE_ONE = 'b';
    protected const string PROTECTED_ONE = 'c';
}
