#!/bin/sh

echo Depends on php-cli. If errors occur, install it.

php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install
