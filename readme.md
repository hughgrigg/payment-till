Payment Till Example Demo
=========================

This is just an experimental demo / example.

## Installation

```bash
composer install
```

## Usage

Different input formats can be sent to different till commands.

```bash
cat ./tests/input/purchases.json | php till json
cat ./tests/input/purchases.xml | php till xml
cat ./tests/input/purchases.csv | php till csv
```

## Tests

```bash
./vendor/phpunit/phpunit/phpunit --testsuite unit
./vendor/phpunit/phpunit/phpunit --testsuite functional
```
