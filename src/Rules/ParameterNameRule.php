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
    /** @var non-empty-string */
    private string $compiledPattern;

    /**
     * Constructs the rule with the given pattern.
     *
     * @throws ShouldNotHappenException
     */
    public function __construct(private string $pattern = '^(id|[a-z]{3,})$')
    {
        $this->compiledPattern = '~' . str_replace('~', '\~', $this->pattern) . '~';

        if (@preg_match($this->compiledPattern, '') === false) {
            throw new ShouldNotHappenException(
                sprintf('Invalid parameter name pattern "%s".', $this->pattern),
            );
        }
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
        $errors = [];

        foreach ($this->parameterNames($node) as [$name, $line]) {
            if (preg_match($this->compiledPattern, $name) === 1) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf('Parameter $%s does not match pattern /%s/.', $name, $this->pattern),
            )
                ->identifier('haspadar.parameterName')
                ->line($line)
                ->build();
        }

        return $errors;
    }

    /**
     * Extracts parameter names and their line numbers from a method node.
     *
     * @return list<array{string, int}>
     */
    private function parameterNames(ClassMethod $node): array
    {
        $names = [];

        foreach ($node->params as $param) {
            if ($param->var instanceof Variable && is_string($param->var->name)) {
                $names[] = [$param->var->name, $param->getStartLine()];
            }
        }

        return $names;
    }
}
