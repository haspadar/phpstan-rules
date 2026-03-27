<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithOverriddenMethodThrows
{
    /**
     * @throws \RuntimeException inherited from parent contract
     */
    #[\Override]
    public function run(): void {}
}
