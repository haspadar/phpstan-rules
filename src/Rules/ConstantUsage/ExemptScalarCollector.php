<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\ConstantUsage;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\NodeFinder;

/**
 * Collects scalar literals that are exempt from the constant usage check.
 * Scalars inside const declarations, PHP attributes, and parameter default
 * values are collected and returned so the rule can skip them.
 */
final class ExemptScalarCollector
{
    /**
     * Returns all scalar nodes inside exempt contexts for the given class.
     *
     * @return list<Scalar\Int_|Scalar\Float_|Scalar\String_>
     */
    public static function collect(NodeFinder $finder, Class_ $node): array
    {
        /** @var list<Node\Stmt\ClassConst|Node\AttributeGroup|Param|Break_|Continue_> $containers */
        $containers = $finder->find(
            $node,
            static fn(Node $n): bool => $n instanceof Node\Stmt\ClassConst
                || $n instanceof Node\AttributeGroup
                || $n instanceof Param
                || $n instanceof Break_
                || $n instanceof Continue_,
        );

        $result = [];

        foreach ($containers as $container) {
            if ($container instanceof Param) {
                $result = array_merge($result, self::paramDefaultScalars($container));

                continue;
            }

            /** @var list<Scalar\Int_|Scalar\Float_|Scalar\String_> $scalars */
            $scalars = $finder->find(
                $container,
                static fn(Node $n): bool => self::isScalarLiteral($n),
            );
            $result = array_merge($result, $scalars);
        }

        return $result;
    }

    /**
     * Extracts scalar nodes from a parameter default value.
     *
     * @return list<Scalar\Int_|Scalar\Float_|Scalar\String_>
     */
    private static function paramDefaultScalars(Param $param): array
    {
        $default = $param->default;

        if ($default instanceof Scalar\Int_ || $default instanceof Scalar\Float_ || $default instanceof Scalar\String_) {
            return [$default];
        }

        if ($default instanceof UnaryMinus && self::isScalarLiteral($default->expr)) {
            assert($default->expr instanceof Scalar\Int_ || $default->expr instanceof Scalar\Float_);

            return [$default->expr];
        }

        if (!$default instanceof Array_) {
            return [];
        }

        /** @var list<Scalar\Int_|Scalar\Float_|Scalar\String_> $scalars */
        $scalars = (new NodeFinder())->find(
            $default,
            static fn(Node $n): bool => self::isScalarLiteral($n),
        );

        return $scalars;
    }

    /**
     * Checks whether a node is a scalar literal (int, float, or string).
     */
    private static function isScalarLiteral(?Node $node): bool
    {
        return $node instanceof Scalar\Int_
            || $node instanceof Scalar\Float_
            || $node instanceof Scalar\String_;
    }
}
