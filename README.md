# Opinionated PHPStan Rules

[![CI](https://github.com/haspadar/phpstan-rules/actions/workflows/piqule.yml/badge.svg)](https://github.com/haspadar/phpstan-rules/actions/workflows/piqule.yml)
[![Coverage](https://codecov.io/gh/haspadar/phpstan-rules/branch/main/graph/badge.svg)](https://codecov.io/gh/haspadar/phpstan-rules)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fhaspadar%2Fphpstan-rules%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/haspadar/phpstan-rules/main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/phpstan-rules?labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

---

## Rules

### Metrics

| Rule                              | Default | Description                                                                |
|-----------------------------------|---------|----------------------------------------------------------------------------|
| `MethodLengthRule`                | 100     | Method body must not exceed N lines                                        |
| `FileLengthRule`                  | 1000    | File must not exceed N lines                                               |
| `TooManyMethodsRule`              | 20      | Class must not have more than N methods                                    |
| `ParameterNumberRule`             | 3       | Method must not have more than N parameters                                |
| `CyclomaticComplexityRule`        | 10      | Method cyclomatic complexity must not exceed N                             |
| `CouplingBetweenObjectsRule`      | 15      | Class must not depend on more than N unique types                          |
| `BooleanExpressionComplexityRule` | 3       | Method must not have more than N boolean operators in a single expression  |
| `StatementCountRule`              | 30      | Method must not have more than N executable statements                     |

### Design

| Rule                              | Description                                                                        |
|-----------------------------------|------------------------------------------------------------------------------------|
| `FinalClassRule`                  | All concrete classes must be `final`                                               |
| `MutableExceptionRule`            | Exception classes must not have non-readonly properties                            |
| `ReturnCountRule`                 | Method must not have more than 1 `return` statement (default: 1)                   |
| `ProtectedMethodInFinalClassRule` | Final classes must not have `protected` methods                                    |
| `ProhibitPublicStaticMethodsRule` | Classes must not have `public static` methods                                      |
| `ConstructorInitializationRule`   | Constructor must only assign `$this->property` or call `parent::__construct()`     |

### Error-prone patterns

| Rule                          | Description                                                                   |
|-------------------------------|-------------------------------------------------------------------------------|
| `NoParameterReassignmentRule` | Method parameters must not be reassigned                                      |
| `IllegalCatchRule`            | Catching `Exception`, `Throwable`, `RuntimeException`, `Error` is forbidden   |
| `IllegalThrowsRule`           | Declaring `@throws Exception` or other broad types in PHPDoc is forbidden     |
| `InnerAssignmentRule`         | Assignment inside conditions (`if ($x = foo())`) is forbidden                 |
| `ModifiedControlVariableRule` | Loop control variable must not be modified inside the loop body               |

### PHPDoc style

| Rule                          | Description                                                                             |
|-------------------------------|-----------------------------------------------------------------------------------------|
| `PhpDocPunctuationClassRule`  | PHPDoc summary of every class must end with `.`, `?`, or `!`                            |
| `PhpDocPunctuationMethodRule` | PHPDoc summary of every method must end with `.`, `?`, or `!`                           |

---

### Configuration

`MethodLengthRule` accepts `maxLines` (int, default `100`) as first constructor argument, and an options array with `skipBlankLines` and `skipComments`.

`FileLengthRule` accepts `maxLines` (int, default `1000`) as first constructor argument, and an options array with `skipBlankLines` and `skipComments`.

`TooManyMethodsRule` accepts `maxMethods` (int, default `20`) as first constructor argument, and an options array with `onlyPublic` (bool, default `false`). When `onlyPublic` is `true`, only public methods are counted.

`ParameterNumberRule` accepts `maxParameters` (int, default `3`) as first constructor argument, and an options array with `ignoreOverridden` (bool, default `true`). When `ignoreOverridden` is `true`, methods with the `#[Override]` attribute are skipped.

`CyclomaticComplexityRule` accepts `maxComplexity` (int, default `10`) as first constructor argument. Counts branches: `if`, `elseif`, `for`, `foreach`, `while`, `do-while`, `case`, `catch`, `&&`, `||`, `and`, `or`, `?:`, `??`, `match` arm.

`CouplingBetweenObjectsRule` accepts `maxCoupling` (int, default `15`) as first constructor argument, and an options array with `excludedClasses` (string[], default `[]`). Counts unique types from property types, parameter types, return types, `new` expressions, static calls, and `catch` type hints.

`BooleanExpressionComplexityRule` accepts `maxOperators` (int, default `3`) as first constructor argument. Counts `&&`, `||`, `and`, `or`, `xor` operators in a single expression. Nested scopes (closures, arrow functions, anonymous classes) are excluded.

`StatementCountRule` accepts `maxStatements` (int, default `30`) as first constructor argument. Counts all executable statements recursively. Nested scopes (closures, arrow functions, anonymous classes, nested functions, property hooks) are excluded.

`ReturnCountRule` accepts `max` (int, default `1`) as first constructor argument. Nested scopes (closures, arrow functions) are excluded from the count.

`IllegalCatchRule` accepts `illegalClassNames` (string[], default `['Exception', 'Throwable', 'RuntimeException', 'Error']`) as first constructor argument.

`IllegalThrowsRule` accepts `illegalClassNames` (string[], default `['Exception', 'Throwable', 'RuntimeException', 'Error']`) as first constructor argument. Names are matched against unresolved short names as written in PHPDoc.

---

## Installation

```bash
composer require --dev haspadar/phpstan-rules
```

---

## Contributing

Fork the repository, apply changes, and open a pull request.

---

## License

MIT
