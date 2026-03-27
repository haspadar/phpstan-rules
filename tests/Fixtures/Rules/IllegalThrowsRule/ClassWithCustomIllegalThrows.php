<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithCustomIllegalThrows
{
    /**
     * @throws LogicException custom illegal type
     */
    public function run(): void {}
}
