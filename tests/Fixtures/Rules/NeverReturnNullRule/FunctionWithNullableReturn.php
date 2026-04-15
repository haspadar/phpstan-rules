<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule;

function findName(): ?string
{
    return 'hello';
}
