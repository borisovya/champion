FROM node:latest

MAINTAINER https://champion.partners

ENV TZ=Europe/Moscow
ENV DEBIAN_FRONTEND="noninteractive"

RUN apt update && \
    apt install vim curl software-properties-common dialog apt-utils libterm-readline-gnu-perl ca-certificates gnupg -y && \
    mkdir -p /etc/apt/keyrings