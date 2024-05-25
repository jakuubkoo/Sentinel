#!/bin/bash

# clean app & cache
php bin/console cache:clear

# delete composer files
rm -rf composer.lock
rm -rf vendor/

# delete symfony cache folder
sudo rm -rf var/

# delete other files
rm -rf .phpcs-cache
