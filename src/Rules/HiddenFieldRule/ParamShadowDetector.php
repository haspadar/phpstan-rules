<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\HiddenFieldRule;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * Inspects method parameters for names that shadow class properties.
 */
final readonly class ParamShadowDetector
{
    /**
     * Returns [paramName, line] pairs for parameters that shadow a property.
     *
     * Promoted parameters are the property itself and never count as shadows.
     *
     * @param ClassMethod $node Method being scanned
     * @param list<string> $propertyNames Names of properties declared on the class
     * @param list<string> $ignoreNames Names to skip even if they match
     * @return list<array{0: string, 1: int}>
     */
    public function detect(ClassMethod $node, array $propertyNames, array $ignoreNames): array
    {
        $result = [];

        foreach ($node->params as $param) {
            if ($this->isPromoted($param)) {
                continue;
            }

            if (!$param->var instanceof Variable || !is_string($param->var->name)) {
                continue;
            }

            $name = $param->var->name;

            if (in_array($name, $ignoreNames, true) || !in_array($name, $propertyNames, true)) {
                continue;
            }

            $result[] = [$name, $param->getStartLine()];
        }

        return $result;
    }

    /**
     * Returns parameter names of the method.
     *
     * @param ClassMethod $node Method whose parameter names are returned
     * @return list<string>
     */
    public function paramNames(ClassMethod $node): array
    {
        $names = [];

        foreach ($node->params as $param) {
            if ($param->var instanceof Variable && is_string($param->var->name)) {
                $names[] = $param->var->name;
            }
        }

        return $names;
    }

    /**
     * Returns true if the parameter is a PHP 8 promoted property.
     */
    private function isPromoted(Node\Param $param): bool
    {
        $mask = Modifiers::PUBLIC | Modifiers::PROTECTED | Modifiers::PRIVATE;

        return ($param->flags & $mask) !== 0;
    }
}
