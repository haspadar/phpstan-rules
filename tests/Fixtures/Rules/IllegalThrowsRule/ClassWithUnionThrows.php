<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithUnionThrows
{
    /**
     * @throws DatabaseException|\RuntimeException
     */
    public function run(): void {}
}
