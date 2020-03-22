#!/usr/bin/env bash

docker build -t rabbitmq-cli .
docker run --rm -it -v $PWD:/usr/src/myapp rabbitmq-cli php composer.phar install
