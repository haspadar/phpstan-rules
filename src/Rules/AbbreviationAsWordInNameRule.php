<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports identifiers that contain too many consecutive capital letters.
 *
 * Checks class names, method names, property names, and parameter names.
 * Constants and enum cases are skipped because PHP convention uses UPPER_SNAKE_CASE for them.
 * Underscores are treated as word separators.
 *
 * @implements Rule<Class_>
 */
final readonly class AbbreviationAsWordInNameRule implements Rule
{
    /** @var list<string> */
    private array $allowedAbbreviations;

    /**
     * Constructs the rule with the given limit and options.
     *
     * @param int $maxAllowedConsecutiveCapitals Maximum consecutive capital letters before an identifier must be split.
     * @param array{
     *     allowedAbbreviations?: list<string>
     * } $options
     */
    public function __construct(
        private int $maxAllowedConsecutiveCapitals = 4,
        array $options = [],
    ) {
        $this->allowedAbbreviations = $options['allowedAbbreviations'] ?? [];
    }

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Class_ $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        return array_merge(
            $this->checkClassName($node),
            $this->collectMemberErrors($node),
        );
    }

    /**
     * Checks the class name itself.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function checkClassName(Class_ $node): array
    {
        return $node->name !== null
            ? $this->buildError($node->name->toString(), $node->getStartLine())
            : [];
    }

    /**
     * Collects errors from methods, parameters, and properties.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function collectMemberErrors(Class_ $node): array
    {
        $errors = [];

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassConst || $stmt instanceof EnumCase) {
                continue;
            }

            $errors = array_merge($errors, $this->checkMethod($stmt), $this->checkProperty($stmt));
        }

        return $errors;
    }

    /**
     * Checks method name and its parameter names.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function checkMethod(Node\Stmt $stmt): array
    {
        if (!$stmt instanceof ClassMethod) {
            return [];
        }

        $errors = $this->buildError($stmt->name->toString(), $stmt->getStartLine());

        foreach ($stmt->params as $param) {
            if ($param->var instanceof Node\Expr\Variable && is_string($param->var->name)) {
                $errors = array_merge($errors, $this->buildError($param->var->name, $param->getStartLine()));
            }
        }

        return $errors;
    }

    /**
     * Checks property names.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function checkProperty(Node\Stmt $stmt): array
    {
        if (!$stmt instanceof Property) {
            return [];
        }

        $errors = [];

        foreach ($stmt->props as $prop) {
            $errors = array_merge($errors, $this->buildError($prop->name->toString(), $prop->getStartLine()));
        }

        return $errors;
    }

    /**
     * Builds an error if the name violates the abbreviation limit.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function buildError(string $name, int $line): array
    {
        foreach (explode('_', $name) as $segment) {
            if ($this->hasViolatingAbbreviation($segment)) {
                return [
                    RuleErrorBuilder::message(
                        sprintf(
                            "Abbreviation in name '%s' must contain no more than %d consecutive capital letters.",
                            $name,
                            $this->maxAllowedConsecutiveCapitals,
                        ),
                    )
                        ->line($line)
                        ->identifier('haspadar.abbreviation')
                        ->build(),
                ];
            }
        }

        return [];
    }

    /**
     * Checks if the segment contains a consecutive-capitals run that violates the limit.
     */
    private function hasViolatingAbbreviation(string $segment): bool
    {
        preg_match_all('/[A-Z]+/', $segment, $matches);

        foreach ($matches[0] as $run) {
            if (strlen($run) <= $this->maxAllowedConsecutiveCapitals) {
                continue;
            }

            if ($this->isAllowedRun($run)) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * Strips allowed abbreviations from the run and checks if the remainder still violates.
     */
    private function isAllowedRun(string $run): bool
    {
        $remainder = $run;

        foreach ($this->allowedAbbreviations as $abbreviation) {
            $remainder = str_ireplace(strtoupper($abbreviation), '', $remainder);
        }

        return strlen($remainder) <= $this->maxAllowedConsecutiveCapitals;
    }
}
