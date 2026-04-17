<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\FewAfferent;

final class Target
{
}

final class UserA
{
    public function __construct(private readonly Target $target)
    {
    }
}

final class UserB
{
    public function __construct(private readonly Target $target)
    {
    }
}
