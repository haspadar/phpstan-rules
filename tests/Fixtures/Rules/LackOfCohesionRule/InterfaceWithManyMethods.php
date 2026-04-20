<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

interface InterfaceWithManyMethods
{
    public function one(): int;

    public function two(): int;

    public function three(): int;

    public function four(): int;

    public function five(): int;

    public function six(): int;

    public function seven(): int;
}
