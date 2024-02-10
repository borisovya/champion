#!/bin/bash

source './support/message.sh'

  message info 'The application stop process has started.'

  command docker compose --env-file ../.env down || { message error 'The application stop has failed' ; exit  1 ; }

  message error 'The application stop process has finished.'
  message error '------------------------------------------'

