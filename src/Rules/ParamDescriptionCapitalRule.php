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
 * Checks that the description of each @param PHPDoc tag in every class method
 * starts with a capital letter. Methods without a PHPDoc block, @param tags
 * without a description, and methods in interfaces and traits are skipped.
 * Uses PhpDocDescriptionChecker to correctly handle generic types with spaces
 * (e.g. array<int, string>).
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ParamDescriptionCapitalRule implements Rule
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

        return $this->collectErrors($docComment->getText(), $node->name->toString());
    }

    /**
     * Collects errors for all @param tags whose description starts with a lowercase letter
     *
     * @throws \PHPStan\ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function collectErrors(string $docText, string $methodName): array
    {
        $errors = [];

        foreach ($this->checker->extractParamDescriptions($docText) as $paramName => $description) {
            if ($this->checker->startsWithCapital($description)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    '@param %s description for %s() must start with a capital letter.',
                    $paramName,
                    $methodName,
                ),
            )
                ->identifier('haspadar.paramCapital')
                ->build();
        }

        return $errors;
    }
}
