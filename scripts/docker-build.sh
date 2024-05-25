#!/bin/bash

# install
sh scripts/install.sh

# build docker containers
docker-compose up --build
