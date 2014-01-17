#!/bin/sh
php app/console cache:clear --env=$1
php app/console assetic:dump --env=$1
chgrp -R www-data ./app/cache/
chown -R www-data ./app/cache/
