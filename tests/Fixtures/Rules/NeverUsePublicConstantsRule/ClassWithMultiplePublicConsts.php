<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

final class ClassWithMultiplePublicConsts
{
    public const string FIRST = 'one';
    public const string SECOND = 'two';
}
