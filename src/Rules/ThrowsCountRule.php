<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports class methods with more @throws PHPDoc tags than the configured maximum.
 *
 * A method declaring many exception types violates the Single Responsibility
 * Principle — each distinct @throws tag signals a different failure mode, which
 * in turn implies a different concern handled by the method. The default limit
 * of 1 enforces one category of error per method.
 *
 * Counts distinct @throws lines in the PHPDoc block. Union types on a single
 * line (e.g. `@throws FooException|BarException`) count as one declaration.
 * Methods without a PHPDoc block or without any @throws tag are not reported.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ThrowsCountRule implements Rule
{
    /**
     * Constructs the rule with the given maximum number of @throws declarations.
     *
     * @param int $maxThrows Maximum number of @throws tags allowed per method
     */
    public function __construct(private int $maxThrows = 1) {}

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
        $docComment = $node->getDocComment();

        if ($docComment === null) {
            return [];
        }

        $count = $this->countThrowsTags($docComment->getText());

        if ($count <= $this->maxThrows) {
            return [];
        }

        $methodName = $node->name->toString();

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s() declares %d @throws types. Maximum allowed is %d.',
                    $methodName,
                    $count,
                    $this->maxThrows,
                ),
            )
                ->identifier('haspadar.throwsCount')
                ->build(),
        ];
    }

    /**
     * Returns the number of @throws lines in the given PHPDoc comment text.
     */
    private function countThrowsTags(string $docComment): int
    {
        $count = 0;

        foreach (explode("\n", $docComment) as $line) {
            if (preg_match('/@throws\s+\S+/', $line) === 1) {
                $count++;
            }
        }

        return $count;
    }
}
