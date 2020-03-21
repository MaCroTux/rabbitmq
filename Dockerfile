FROM php:7.3-cli

RUN apt update && apt install -y git

RUN docker-php-ext-install sockets mbstring bcmath

WORKDIR /usr/src/myapp