# AGENTS.md

## Project Overview

A PHP library for generating and parsing RSS 2.0 feeds (`gemorroj/rss`). It builds
RSS 2.0 XML documents programmatically and parses RSS 2.0 XML back into objects.

- Namespace: `Rss\` (PSR-4, mapped to `src/`), value objects under `Rss\Model\` (`src/Model/`)
- Tests namespace: `Rss\Tests\` (PSR-4, mapped to `tests/`)
- Classes: `Rss\Generator` (generator), `Rss\Parser` (RSS 2.0 XML → `Rss\Model\Feed`),
  value objects `Rss\Model\Feed`, `Rss\Model\Item`, `Rss\Model\Enclosure`

## Requirements

- PHP >= 8.2
- ext-dom
- ext-libxml

## Commands

```bash
# Install dependencies
composer install

# Run the test suite
vendor/bin/phpunit --configuration phpunit.xml.dist

# Run code style checks / fixes
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run --diff
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php
```

## Code Style

The project uses PHP-CS-Fixer with the `@Symfony` ruleset (plus risky rules, PHP 8.2
migration rules, and PHPUnit 11 migration rules). See `.php-cs-fixer.dist.php` for the
exact configuration. Run the fixer before committing.

## Testing

Tests use PHPUnit and live in `tests/` under the `Rss\Tests\` namespace.
Add or update tests for any behavior change.

## Conventions

- Do not add comments unless asked.
- Follow existing code style and structure.
- Keep the public API minimal and backwards-compatible.
