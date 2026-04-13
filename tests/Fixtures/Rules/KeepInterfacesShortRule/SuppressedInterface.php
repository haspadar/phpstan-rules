<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\KeepInterfacesShortRule;

/** @phpstan-ignore haspadar.interfaceMethods */
interface SuppressedInterface
{
    public function one(): void;

    public function two(): void;

    public function three(): void;

    public function four(): void;
}
