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
 * Checks that every parameter of a method with a PHPDoc block has a matching `@param` tag.
 * If a method has no PHPDoc, the rule reports nothing — the absence of the block itself is
 * handled by PhpDocMissingMethodRule. When a block is present, each parameter of the signature
 * must be documented by a matching `@param` tag; otherwise the contract is incomplete.
 * Non-public methods are skipped when checkPublicOnly is true (default). Methods with the
 * #[Override] attribute are skipped when skipOverridden is true (default) because the param
 * contract is inherited from the base method.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class PhpDocMissingParamRule implements Rule
{
    private bool $checkPublicOnly;

    private bool $skipOverridden;

    private PhpDocDescriptionChecker $checker;

    /**
     * Constructs the rule with visibility and override options, initialising the shared PHPDoc checker.
     *
     * @param array{checkPublicOnly?: bool, skipOverridden?: bool} $options Visibility filter and `#[Override]` skip flag.
     * @throws ShouldNotHappenException
     */
    public function __construct(array $options = [])
    {
        $this->checkPublicOnly = $options['checkPublicOnly'] ?? true;
        $this->skipOverridden = $options['skipOverridden'] ?? true;
        $this->checker = new PhpDocDescriptionChecker();
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors, one per undocumented parameter.
     *
     * @psalm-param ClassMethod $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $docComment = $node->getDocComment();

        if ($this->shouldSkip($node, $scope) || $docComment === null || $node->params === []) {
            return [];
        }

        $documented = $this->checker->extractParamNames($docComment->getText());

        return $this->collectMissing($node, $documented);
    }

    /**
     * Returns true when the rule must not inspect the given method (wrong scope, filtered by options, etc).
     */
    private function shouldSkip(ClassMethod $node, Scope $scope): bool
    {
        $reflection = $scope->getClassReflection();

        return $reflection === null
            || !$reflection->isClass()
            || ($this->checkPublicOnly && !$node->isPublic())
            || ($this->skipOverridden && $this->hasOverrideAttribute($node));
    }

    /**
     * Builds one error per parameter of the method that is not listed in the documented set.
     *
     * @param ClassMethod $node Method whose parameters are validated against documented tags.
     * @param list<string> $documented Parameter names (with leading `$`) already present in @param tags.
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function collectMissing(ClassMethod $node, array $documented): array
    {
        $methodName = $node->name->toString();
        $errors = [];

        foreach ($node->params as $param) {
            $var = $param->var;

            if (!$var instanceof Variable || !is_string($var->name)) {
                continue;
            }

            $paramName = sprintf('$%s', $var->name);

            if (in_array($paramName, $documented, true)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'PHPDoc for %s() is missing @param for parameter %s.',
                    $methodName,
                    $paramName,
                ),
            )
                ->identifier('haspadar.phpdocMissingParam')
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if the method has the #[Override] attribute (with or without leading backslash).
     */
    private function hasOverrideAttribute(ClassMethod $node): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if (in_array($attr->name->toString(), ['Override', '\Override'], true)) {
                    return true;
                }
            }
        }

        return false;
    }
}
