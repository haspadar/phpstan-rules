# PHPStan Rules — design rules for immutability and structure

[![CI](https://github.com/haspadar/phpstan-rules/actions/workflows/ci.yml/badge.svg)](https://github.com/haspadar/phpstan-rules/actions/workflows/ci.yml)
[![PHP](https://img.shields.io/packagist/php-v/haspadar/phpstan-rules)](https://packagist.org/packages/haspadar/phpstan-rules)

---

## Rules

| Rule               | Constraint                            |
|--------------------|---------------------------------------|
| `MethodLengthRule` | Method body must not exceed 100 lines |

`MethodLengthRule` accepts `maxLines` (int, default `100`) as first constructor argument, and an options array with `skipBlankLines` and `skipComments`.

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
