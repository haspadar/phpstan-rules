<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

class BaseClass
{
    /** @param string $message */
    public static function initialize(string $message): void {}
}

final class ClassWithParentMethodCallInConstructor extends BaseClass
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
        parent::initialize($message);
    }
}
