<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

interface InterfaceWithWrongOrder
{
    /**
     * Saves the user.
     *
     * @throws \RuntimeException When saving fails.
     *
     * @return void
     */
    public function save(): void;
}
