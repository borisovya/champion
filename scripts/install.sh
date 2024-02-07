#!/bin/bash

source './support/message.sh'

message info 'The application startup process has started.'

message info 'Step 1: Build backend base environment'
command docker build -t champion_backend_base "../infra/php" || { message error 'Docker backend base image install was failed' ; exit  1 ; }

message info 'Step 2: Build frontend base environment.'
command docker build -t champion_frontend_base "../infra/frontend" || { message error 'Docker frontend base image install was failed' ; exit  1 ; }

cd ..

message info 'Step 3: Build services'
command docker compose -f compose-dev.yml build || { message error 'Docker services build was failed' ; exit  1 ; }

message info 'Step 3: Run services'
command docker compose -f compose-dev.yml up -d || { message error 'Docker services run was failed' ; exit  1 ; }