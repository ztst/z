#!/bin/sh

if [ "$1" = "prod" -o "$1" = "dev" -o "$1" = "test" ] ; then
  php app/console cache:clear --env=$1
  if [ "$2" = "web" ] ; then
    php app/console assetic:dump --env=$1
  fi
  chgrp -R www-data ./app/cache/
  chown -R www-data ./app/cache/
fi