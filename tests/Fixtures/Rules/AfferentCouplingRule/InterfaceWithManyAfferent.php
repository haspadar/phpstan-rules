<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\InterfaceWithManyAfferent;

interface HotInterface
{
    public function ping(): string;
}

final class ImplementorOne implements HotInterface
{
    public function ping(): string
    {
        return 'one';
    }
}

final class ImplementorTwo implements HotInterface
{
    public function ping(): string
    {
        return 'two';
    }
}

final class ImplementorThree implements HotInterface
{
    public function ping(): string
    {
        return 'three';
    }
}
