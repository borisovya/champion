#!/bin/bash

source './support/message.sh'

message info 'The application restart process has started.'

command docker compose up --force-recreate -d || { message error 'The application startup has failed' ; exit  1 ; }

cd ../champion-backend || exit 1

message info 'Install backend.'
command composer install --prefer-dist --no-interaction
command php bin/console cache:clear

cd ../champion-frontend || exit 1

message info 'Install frontend.'
command yarn

cd .. || exit 1

message info 'Install backend into container.'
docker compose exec backend composer install --prefer-dist --no-interaction
docker compose exec backend php bin/console --no-interaction doctrine:migrations:migrate

message info 'Install frontend into container.'
docker compose exec frontend yarn
