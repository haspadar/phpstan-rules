<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParamDescriptionCapitalRule;

final class MethodWithTwoLowercaseParams
{
    /**
     * Returns something based on name and age.
     *
     * @param string $name user name to process
     * @param int    $age  age of the user
     *
     * @return string
     */
    public function getName(string $name, int $age): string
    {
        return $name . $age;
    }
}
