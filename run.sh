#!/usr/bin/env bash

docker-compose up -d rabbit-manager && docker-compose logs -f rabbit-manager