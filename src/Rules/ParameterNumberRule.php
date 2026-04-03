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
 * Reports a class method that has more parameters than the configured maximum.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ParameterNumberRule implements Rule
{
    private bool $ignoreOverridden;

    /**
     * Constructs the rule with the given parameter limit and options.
     *
     * @param array{
     *     ignoreOverridden?: bool
     * } $options
     */
    public function __construct(private int $maxParameters = 3, array $options = [])
    {
        $this->ignoreOverridden = $options['ignoreOverridden'] ?? true;
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        if ($this->ignoreOverridden && $this->hasOverrideAttribute($node)) {
            return [];
        }

        $count = count($node->params);

        if ($count <= $this->maxParameters) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection !== null
            ? $reflection->getName()
            : 'unknown';

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() has %d parameters. Maximum allowed is %d.',
                    $className,
                    $node->name->toString(),
                    $count,
                    $this->maxParameters,
                ),
            )
                ->identifier('haspadar.parameterNumber')
                ->build(),
        ];
    }

    /** Checks whether the method has the #[Override] attribute. */
    private function hasOverrideAttribute(ClassMethod $node): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if ($attr->name->toString() === 'Override') {
                    return true;
                }
            }
        }

        return false;
    }
}
