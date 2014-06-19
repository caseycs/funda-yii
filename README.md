# Funda.nl API job interview task

## comments&readme

...

## Installation

You need:

* mysql with `insided` and `insided_test` databases and restored dump from `app/data/schema.mysql.sql`
* memcached
* php with proper extensions
* start on localhost with built-in php 5.5 server: `php -S localhost:8888 -t public/ app/index.php`

## todo

* write comments&readme - why i choose this approach?
* ~~tricky api - how to determine pages count?~~
* ~~general task - back-end~~
* general task - front-end
* unit tests - db storage
* ~~unit tests - memcache-based RPM limiter~~
* selenium tests - front web UI
* running on production
* ~~memcache for rpm limit~~
* ~~console logging from yii~~
* psr codestyle
* psr autoload/namespaces
* code comments
* ~~repo on github~~
* ~~common config files~~
* salt for server side config
* capistrano/??? for deploy
