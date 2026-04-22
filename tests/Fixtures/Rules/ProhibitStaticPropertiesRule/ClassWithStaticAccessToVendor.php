<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

final class ClassWithStaticAccessToVendor
{
    public function format(\DateTimeImmutable $date): string
    {
        return $date->format(\DateTimeInterface::ATOM);
    }
}
