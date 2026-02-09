# Installation

```bash
composer require antipodes/php-cs-fixer-config-antipodes --dev
```

## Usage

### Configuration

Pick one of the rule sets:

- [`Antipodes\PhpCsFixer\Config\RuleSet\Php74`](src/RuleSet/Php74.php)

Create a configuration file `.php-cs-fixer.dist.php` in the root of your project:

```php
<?php

use PhpCsFixer\Finder;
use Antipodes\PhpCsFixer\Config\Factory;
use Antipodes\PhpCsFixer\Config\RuleSet\Php74;

$config = Factory::fromRuleSet(new Php74());

$finder = Finder::create()
                ->in([
                    __DIR__,
                ])
                ->name('*.php')
                ->ignoreDotFiles(true)
                ->ignoreVCS(true);

return $config
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
```

### Configuration with override rules

:bulb: Optionally override rules from a rule set by passing in an array of rules to be merged in:

```diff
<?php

use PhpCsFixer\Finder;
use Antipodes\PhpCsFixer\Config\Factory;
use Antipodes\PhpCsFixer\Config\RuleSet\Php74;

-$config = Factory::fromRuleSet(new Php74());
+$config = Factory::fromRuleSet(new Php74(), [
+    'mb_str_functions' => false,
+    'strict_comparison' => false,
+]);

$finder = Finder::create()
                ->in([
                    __DIR__,
                ])
                ->name('*.php')
                ->ignoreDotFiles(true)
                ->ignoreVCS(true);

return $config
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
```

### Composer script

If you like [`composer` scripts](https://getcomposer.org/doc/articles/scripts.md), add a `coding-standards` script to `composer.json`:

```diff
 {
   "name": "foo/bar",
   "require": {
     "php": "^7.3",
   },
   "require-dev": {
     "antipodes/php-cs-fixer-config-antipodes": "~1.0.0"
+  },
+  "scripts": {
+    "lint": [
+      "./vendor/bin/php-cs-fixer fix -vvv --dry-run --show-progress=dots"
+    ],
+    "lint:fix": [
+      "./vendor/bin/php-cs-fixer fix -vvv --show-progress=dots"
+    ]
   }
 }
```

Run

```
composer lint
```

to lint the code.

Run

```
composer lint:fix
```

to automatically fix the code.

### GitHub Actions

If you like [GitHub Actions](https://github.com/features/actions), add a `php-lint` job to your workflow:

```yml
name: "🚨 PHP Linter"

on:
  push:
    branches:
      - master
  pull_request:
    branches:
      - master

jobs:
  php:
    name: PHP ${{ matrix.php }}
    runs-on: ubuntu-latest
    if: "!contains(github.event.head_commit.message, '[ci skip]')"
    strategy:
      fail-fast: false
      matrix:
        php: [ "7.4", "8.0" ]

    steps:
      - name: Checkout the project
        uses: actions/checkout@v2

      - name: Setup the PHP ${{ matrix.php }} environment on ${{ runner.os }}
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}

      - name: Add HTTP basic auth credentials
        run: echo '${{ secrets.COMPOSER_AUTH_JSON }}' > $GITHUB_WORKSPACE/auth.json

      - name: Install Composer dependencies
        run: composer install --no-progress --prefer-dist --optimize-autoloader --no-suggest

      - name: Execute the lint script
        run: composer run-script lint
```
"# php-cs-fixer-config-opentech" 
