<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

final class SuppressedPublicConst
{
    /** @phpstan-ignore haspadar.noPublicConstants */
    public const string NAME = 'test';
}
