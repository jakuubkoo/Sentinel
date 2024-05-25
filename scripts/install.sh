#!/bin/bash

# install all application requirements

# install composer
if [ ! -d 'vendor/' ]
then
    composer install
fi

# fix storage permissions
sudo chmod -R 777 var/
sudo chown -R www-data:www-data var/
