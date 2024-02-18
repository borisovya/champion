#!/bin/bash

source './support/message.sh'

message info 'The application restart process has started.'

command docker compose up --force-recreate -d || { message error 'The application startup has failed' ; exit  1 ; }

cd ../champion-backend || exit 1

command composer install --prefer-dist --no-interaction

docker compose exec backend php bin/console --no-interaction doctrine:migrations:migrate

command php bin/console cache:clear