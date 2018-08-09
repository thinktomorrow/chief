[Install](/index.md)
[Local development](/chief-development.md)
[Pages](pages/index.md)
[Modules](modules/index.md)
[Server](/server.md)
[Changelog](/CHANGELOG.md)
[Guidelines](/GUIDELINES.md)
# Standard deployment scripts

## PRODUCTION
```bash

# set the production environment
cp .env.production .env

# install packages and dump autoload
composer install

# clear cache
php artisan cache:clear
```

## STAGING
```bash

# set the staging environment
cp .env.staging .env

# install packages and dump autoload
composer install

# disallow index on staging
cp public/robots-staging.txt public/robots.txt

# clear cache
php artisan cache:clear
```
