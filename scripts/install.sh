#!/bin/bash

source './support/message.sh'

message info 'The application install process has started.'

message info 'Step 1: Build backend base environment.'
command docker build -t champion-backend-base "../infra/php" || { message error 'Docker backend base image install was failed' ; exit  1 ; }

cd ../champion-backend || exit 1

command composer install

message info 'Step 2: Build frontend base environment.'
command docker build -t champion-frontend-base "../infra/frontend" || { message error 'Docker frontend base image install was failed' ; exit  1 ; }

cd ../champion-frontend || exit 1

command yarn

cd ..

message info 'Step 3: Build services.'
command docker compose build || { message error 'Docker services build was failed' ; exit  1 ; }

message info 'Step 3: Run services.'
command docker compose up -d || { message error 'Docker services run was failed' ; exit  1 ; }