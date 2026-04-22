<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

/** Stub that simulates a third-party class exposing a public static property */
final class VendorStaticCarrier
{
    /** @phpstan-ignore haspadar.staticProperty */
    public static string $translator = 'en';
}

final class ClassWithStaticAccessToVendor
{
    public function useTranslator(): string
    {
        VendorStaticCarrier::$translator = 'ru';
        return VendorStaticCarrier::$translator;
    }
}
