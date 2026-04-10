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
 * Checks that local variable names match a configurable regex pattern.
 * Validates assignments, foreach, for, destructuring, and static variables.
 * Skips parameters, catch, $this, and nested scopes.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class VariableNameRule implements Rule
{
    /** @var list<string> */
    private array $allowedNames;

    /**
     * Constructs the rule with the given pattern and options.
     *
     * @param array{allowedNames?: list<string>} $options
     */
    public function __construct(
        private string $pattern = '^[a-z][a-zA-Z]{2,19}$',
        array $options = [],
    ) {
        $this->allowedNames = $options['allowedNames'] ?? ['id', 'i', 'j'];
    }

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
        $paramNames = $this->parameterNames($node);
        $errors = [];
        $checked = [];

        foreach ((new VariableCollector())->collect($node) as [$name, $line]) {
            if ($name === 'this' || in_array($name, $paramNames, true)) {
                continue;
            }

            if (array_key_exists($name, $checked)) {
                continue;
            }

            $checked[$name] = true;

            if (in_array($name, $this->allowedNames, true)) {
                continue;
            }

            if (preg_match('/' . $this->pattern . '/', $name) === 1) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf('Variable $%s does not match pattern /%s/.', $name, $this->pattern),
            )
                ->identifier('haspadar.variableName')
                ->line($line)
                ->build();
        }

        return $errors;
    }

    /**
     * Returns parameter names for the given method node.
     *
     * @return list<string>
     */
    private function parameterNames(ClassMethod $node): array
    {
        $names = [];

        foreach ($node->params as $param) {
            if ($param->var instanceof Variable && is_string($param->var->name)) {
                $names[] = $param->var->name;
            }
        }

        return $names;
    }
}
