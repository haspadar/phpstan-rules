# PHPStan Rules — design rules for immutability and structure

[![CI](https://github.com/haspadar/phpstan-rules/actions/workflows/ci.yml/badge.svg)](https://github.com/haspadar/phpstan-rules/actions/workflows/ci.yml)
[![PHP](https://img.shields.io/packagist/php-v/haspadar/phpstan-rules)](https://packagist.org/packages/haspadar/phpstan-rules)

---

## Rules

| Rule              | Constraint                           |
|-------------------|--------------------------------------|
| `MethodLinesRule` | Method body must not exceed 50 lines |
| `FileLengthRule`  | File must not exceed 1000 lines      |

`MethodLinesRule` accepts an options array with `maxLines`, `skipBlankLines`, and `skipComments`.

`FileLengthRule` accepts `maxLines` (int, default `1000`) as first constructor argument, and an options array with `skipBlankLines` and `skipComments`.

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
