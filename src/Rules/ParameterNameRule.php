<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Checks that method parameter names match a configurable regex pattern.
 * Validates parameters of class methods, including promoted properties.
 * Skips closures and arrow functions.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ParameterNameRule implements Rule
{
    /**
     * Constructs the rule with the given pattern.
     */
    public function __construct(private string $pattern = '^(id|[a-z]{3,})$') {}

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param ClassMethod $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        foreach ($node->params as $param) {
            if (!$param->var instanceof Variable || !is_string($param->var->name)) {
                continue;
            }

            $name = $param->var->name;

            if (preg_match('/' . $this->pattern . '/', $name) === 1) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf('Parameter $%s does not match pattern /%s/.', $name, $this->pattern),
            )
                ->identifier('haspadar.parameterName')
                ->line($param->getStartLine())
                ->build();
        }

        return $errors;
    }
}
