#!/bin/sh
set -e

yarn install && yarn install --only=dev

if [ "${1#-}" != "${1}" ] || [ -z "$(command -v "${1}")" ]; then
  set -- node "$@"
fi

exec "$@"