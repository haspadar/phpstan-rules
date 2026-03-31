<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParamDescriptionCapitalRule;

final class MethodWithLowercaseParam
{
    /**
     * Returns something based on the name.
     *
     * @param string $name user name to process
     *
     * @return string
     */
    public function getName(string $name): string
    {
        return $name;
    }
}
