<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithTrailingUnionThrows
{
    /**
     * @throws DatabaseException|
     */
    public function run(): void {}
}
