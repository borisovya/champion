#!/bin/bash

source './support/message.sh'

message info 'The application uninstall process has started.'

command docker rm --force -v champion-db
command docker rm --force -v champion-frontend
command docker rm --force -v champion-backend
command docker rm --force -v champion-server

command docker rmi champion-frontend champion-frontend-base champion-backend champion-backend-base champion-db
command docker volume prune -f

message error 'The application uninstall process has finished.'
message error '------------------------------------------'