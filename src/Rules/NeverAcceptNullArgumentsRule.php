<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports nullable parameters in methods and standalone functions.
 *
 * Detects three patterns:
 * - `?Type` (NullableType)
 * - `Type|null` (UnionType containing null)
 * - `$param = null` (null default value)
 *
 * Closures and arrow functions are excluded because they commonly
 * use nullable parameters for optional callbacks.
 *
 * @implements Rule<Stmt>
 */
final readonly class NeverAcceptNullArgumentsRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Stmt::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @param Stmt $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof ClassMethod && !$node instanceof Function_) {
            return [];
        }

        $errors = [];

        foreach ($node->params as $param) {
            if (!$this->isNullable($param)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Parameter $%s in %s must not be nullable.',
                    $this->parameterName($param),
                    $this->functionLabel($node, $scope),
                ),
            )
                ->identifier('haspadar.noNullArguments')
                ->line($param->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true when the parameter accepts null via type or default value.
     */
    private function isNullable(Param $param): bool
    {
        if ($param->type instanceof NullableType) {
            return true;
        }

        if ($param->type instanceof UnionType) {
            foreach ($param->type->types as $type) {
                if ($type instanceof Identifier && $type->toLowerString() === 'null') {
                    return true;
                }
            }
        }

        return $param->default instanceof ConstFetch && $param->default->name->toLowerString() === 'null';
    }

    /**
     * Extracts the parameter name as a string.
     */
    private function parameterName(Param $param): string
    {
        assert($param->var instanceof Variable && is_string($param->var->name));

        return $param->var->name;
    }

    /**
     * Returns a human-readable label for the enclosing function or method.
     *
     * @param ClassMethod|Function_ $node
     */
    private function functionLabel(FunctionLike $node, Scope $scope): string
    {
        if ($node instanceof ClassMethod) {
            $classReflection = $scope->getClassReflection();
            assert($classReflection !== null);

            return sprintf('method %s::%s()', $classReflection->getName(), $node->name->toString());
        }

        return sprintf('function %s()', $node->namespacedName ?? $node->name->toString());
    }
}
