#!/bin/bash

cd ./tests/server 

ENV=testing php -S 127.0.0.1:9540 -c php.ini > /dev/null 2>&1 &

cd ../..

./vendor/bin/phpunit

killall -9 php