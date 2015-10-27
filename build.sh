#!/usr/bin/env bash

# composer install --no-dev --optimize-autoloader --no-interaction
composer install --optimize-autoloader --no-interaction &&
php app/console doctrine:migration:migrate -n &&
npm install &&
node_modules/.bin/bower install &&
node_modules/.bin/gulp build
