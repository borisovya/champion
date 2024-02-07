FROM champion_frontend_base as dev

WORKDIR /var/www/champion

COPY . .

CMD [ "sh", "-c", "yarn install" ]
COPY ./dev-entrypoint.sh /usr/local/bin/

ENTRYPOINT ["bash", "dev-entrypoint.sh"]

CMD [ "sh", "-c", "yarn install && yarn build" ]

EXPOSE 8080


FROM champion_frontend_base as production

WORKDIR /var/www/champion

COPY . .

CMD [ "sh", "-c", "yarn install && yarn build" ]

EXPOSE 8080
