#!/bin/sh
#php app/console doctrine:database:drop --env=test --force
#php app/console doctrine:database:create --env=test
#php app/console doctrine:schema:update --force --env=test
#php app/console doctrine:fixtures:load --env=test
./vendor/phpunit/phpunit/phpunit.php -c app/
