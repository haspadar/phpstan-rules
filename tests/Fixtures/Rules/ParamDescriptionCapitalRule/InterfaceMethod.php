<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParamDescriptionCapitalRule;

interface InterfaceMethod
{
    /**
     * Returns something based on the name.
     *
     * @param string $name user name to process
     *
     * @return string
     */
    public function getName(string $name): string;
}
