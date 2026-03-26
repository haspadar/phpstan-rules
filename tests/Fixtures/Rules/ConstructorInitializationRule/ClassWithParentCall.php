<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithParentCall extends \RuntimeException
{
    public function __construct(string $message)
    {
        $this->message = $message;
        parent::__construct($message);
    }
}
