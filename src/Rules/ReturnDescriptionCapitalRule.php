<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Checks that the description of the @return PHPDoc tag in every class method
 * starts with a capital letter. Methods without a PHPDoc block, @return tags
 * without a description, and methods in interfaces and traits are skipped.
 * Uses PhpDocDescriptionChecker to correctly handle generic types with spaces
 * (e.g. array<int, string>).
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ReturnDescriptionCapitalRule implements Rule
{
    private PhpDocDescriptionChecker $checker;

    /** @throws \PHPStan\ShouldNotHappenException */
    public function __construct()
    {
        $this->checker = new PhpDocDescriptionChecker();
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-param ClassMethod $node
     * @throws \PHPStan\ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $scope->getClassReflection();

        $docComment = $node->getDocComment();

        if ($reflection === null || !$reflection->isClass() || $docComment === null) {
            return [];
        }

        $description = $this->checker->extractReturnDescription($docComment->getText());

        if ($description === null || $this->checker->startsWithCapital($description)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    '@return description for %s() must start with a capital letter.',
                    $node->name->toString(),
                ),
            )
                ->identifier('haspadar.returnCapital')
                ->build(),
        ];
    }
}
