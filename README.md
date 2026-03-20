# PHPStan Rules — design rules for immutability and structure

[![CI](https://github.com/haspadar/phpstan-rules/actions/workflows/piqule.yml/badge.svg)](https://github.com/haspadar/phpstan-rules/actions/workflows/piqule.yml)
[![Coverage](https://codecov.io/gh/haspadar/phpstan-rules/branch/main/graph/badge.svg)](https://codecov.io/gh/haspadar/phpstan-rules)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fhaspadar%2Fphpstan-rules%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/haspadar/phpstan-rules/main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/phpstan-rules?labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

---

## Rules

| Rule                 | Constraint                              |
|----------------------|-----------------------------------------|
| `MethodLengthRule`   | Method body must not exceed 100 lines   |
| `FileLengthRule`     | File must not exceed 1000 lines         |
| `TooManyMethodsRule` | Class must not have more than 20 methods |

`MethodLengthRule` accepts `maxLines` (int, default `100`) as first constructor argument, and an options array with `skipBlankLines` and `skipComments`.

`FileLengthRule` accepts `maxLines` (int, default `1000`) as first constructor argument, and an options array with `skipBlankLines` and `skipComments`.

`TooManyMethodsRule` accepts `maxMethods` (int, default `20`) as first constructor argument, and an options array with `onlyPublic`.

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
