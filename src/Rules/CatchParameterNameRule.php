<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Catch_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Checks that catch block parameter names match a configurable regex pattern.
 * Skips unnamed catch blocks (PHP 8.0+).
 *
 * @implements Rule<Catch_>
 */
final readonly class CatchParameterNameRule implements Rule
{
    /** @var non-empty-string */
    private string $compiledPattern;

    /**
     * Constructs the rule with the given pattern.
     *
     * @param string $pattern Regex (without delimiters) that every catch parameter name must match.
     * @throws ShouldNotHappenException
     */
    public function __construct(private string $pattern = '^(e|ex|[a-z]{3,12})$')
    {
        $this->compiledPattern = (new CompiledPattern())->from($this->pattern, 'catch parameter name');
    }

    #[Override]
    public function getNodeType(): string
    {
        return Catch_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Catch_ $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $name = $this->extractName($node);

        if ($name === null) {
            return [];
        }

        if (preg_match($this->compiledPattern, $name) === 1) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf('Catch parameter $%s does not match pattern /%s/.', $name, $this->pattern),
            )
                ->identifier('haspadar.catchParamName')
                ->build(),
        ];
    }

    /**
     * Extracts the catch parameter name, or null if unnamed.
     */
    private function extractName(Catch_ $node): ?string
    {
        if (!$node->var instanceof Variable || !is_string($node->var->name)) {
            return null;
        }

        return $node->var->name;
    }
}
