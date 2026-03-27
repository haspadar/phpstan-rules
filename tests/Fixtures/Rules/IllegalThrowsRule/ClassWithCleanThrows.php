<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithCleanThrows
{
    /**
     * @throws DatabaseException when connection is lost
     */
    public function run(): void {}
}
