#!/bin/bash

source './support/message.sh'

message info 'The application restart process has started.'

command docker compose up --force-recreate -d || { message error 'The application startup has failed' ; exit  1 ; }