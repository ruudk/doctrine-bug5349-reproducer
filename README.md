# Reproducer

See https://github.com/doctrine/dbal/issues/5349

## Installation

```bash
composer install
cp config.php.dist config.php
```

Edit `config.php` with your credentials.

Then run `php orm.php` to see the problem in ORM.

I also tried to reproduce this in DBAL but couldn't get to work.
