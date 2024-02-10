FROM champion-frontend-base as dev

WORKDIR /var/www/champion

COPY . .

RUN yarn

CMD [ "sh", "-c", "yarn dev" ]

EXPOSE 8080


FROM champion-frontend-base as production

WORKDIR /var/www/champion

COPY . .

RUN yarn

CMD [ "sh", "-c", "yarn start" ]

EXPOSE 8080
