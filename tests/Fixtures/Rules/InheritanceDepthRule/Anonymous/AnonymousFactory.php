<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InheritanceDepthRule\Anonymous;

final class AnonymousFactory
{
    public function make(): AnonymousBase
    {
        return new class extends AnonymousBase {
        };
    }
}
