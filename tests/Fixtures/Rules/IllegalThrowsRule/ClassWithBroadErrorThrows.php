<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithBroadErrorThrows
{
    /**
     * @throws \Error on fatal error
     */
    public function run(): void {}
}
