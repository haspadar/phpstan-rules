# Opinionated PHPStan Rules

[![CI](https://github.com/haspadar/phpstan-rules/actions/workflows/piqule.yml/badge.svg)](https://github.com/haspadar/phpstan-rules/actions/workflows/piqule.yml)
[![Coverage](https://codecov.io/gh/haspadar/phpstan-rules/branch/main/graph/badge.svg)](https://codecov.io/gh/haspadar/phpstan-rules)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fhaspadar%2Fphpstan-rules%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/haspadar/phpstan-rules/main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/phpstan-rules?labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

---

## Rules

| Rule                              | Constraint                                                               |
|-----------------------------------|--------------------------------------------------------------------------|
| `MethodLengthRule`                | Method body must not exceed 100 lines                                    |
| `FileLengthRule`                  | File must not exceed 1000 lines                                          |
| `TooManyMethodsRule`              | Class must not have more than 20 methods                                 |
| `ParameterNumberRule`             | Method must not have more than 3 parameters                              |
| `BooleanExpressionComplexityRule` | Method must not have more than 3 boolean operators in a single expression |
| `StatementCountRule`              | Method must not have more than 30 executable statements                  |

`MethodLengthRule` accepts `maxLines` (int, default `100`) as first constructor argument, and an options array with `skipBlankLines` and `skipComments`.

`FileLengthRule` accepts `maxLines` (int, default `1000`) as first constructor argument, and an options array with `skipBlankLines` and `skipComments`.

`TooManyMethodsRule` accepts `maxMethods` (int, default `20`) as first constructor argument, and an options array with `onlyPublic` (bool, default `false`). When `onlyPublic` is `true`, only public methods are counted.

`ParameterNumberRule` accepts `maxParameters` (int, default `3`) as first constructor argument, and an options array with `ignoreOverridden` (bool, default `true`). When `ignoreOverridden` is `true`, methods with the `#[Override]` attribute are skipped.

`BooleanExpressionComplexityRule` accepts `maxOperators` (int, default `3`) as first constructor argument. Counts `&&`, `||`, `and`, `or`, `xor` operators in a single expression. Nested scopes (closures, arrow functions, anonymous classes) are excluded.

`StatementCountRule` accepts `maxStatements` (int, default `30`) as first constructor argument. Counts all executable statements recursively. Nested scopes (closures, arrow functions, anonymous classes, nested functions, property hooks) are excluded.

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
