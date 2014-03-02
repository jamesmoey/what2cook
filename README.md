INSTALLATION
============
You need composer to install this web application.

Download Composer on *nix
```bash
curl -sS https://getcomposer.org/installer | php
```
Check out https://getcomposer.org/doc/00-intro.md for more help

Install dependency
```bash
php composer.phar install --dev
```

Run Unit Testing
```bash
./bin/phpunit -c app
```

Copy web asset to correct folder and Run PHP Server in Production mode
```bash
app/console assetic:dump
app/console server:run --env=prod
```

Once you started the PHP Server. You can goto http://localhost:8000 to access the web application.