#!/bin/bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    (cd /srv/app && php artisan config:cache && php artisan route:cache && php artisan event:cache)
fi
if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    (cd /srv/app && php artisan config:cache)
fi

if [ "$role" = "app" ]; then
    exec apache2-foreground

elif [ "$role" = "db-migrator" ]; then

    echo "Running the database migration..." 
    yes | php /srv/app/artisan migrate

elif [ "$role" = "queue" ]; then

    echo "Running the queue..." 
    php /srv/app/artisan queue:work redis --sleep=1 --tries=3
    # php /srv/app/artisan queue:work database --queue=emails,default --verbose --tries=3 --timeout=90
elif [ "$role" = "pub-sub" ]; then

    echo "Running the pub-sub..." 
    php /srv/app/artisan redis:subscribe

elif [ "$role" = "kafka-pub-sub" ]; then

    echo "Running the pub-sub..." 
    php /srv/app/artisan kafka:consume

elif [ "$role" = "scheduler" ]; then

    echo "Starting scheduler process :)"
    echo "Starting scheduler process :)" > /srv/app/storage/logs/laravel-scheduler.log
    ln -sf /proc/1/fd/1 /srv/app/storage/logs/laravel-scheduler.log
    while [ true ]
    do
      php /srv/app/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
