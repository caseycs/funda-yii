# Funda.nl API job interview task

## Comments

### Why this is simple task is co complicated?

According to assignment description, code should be OO, DRY and reusable.
So i decided better to overestimate this requirements.

That means, that of course i can simplify this solution, use less DB tables, avoid memcache for Funda API limit and so on.

### Common architecture

We have cron script, that time-to-time makes query to Funda.nl and retrieves all pages and stores in DB.
On the front - we just retrieve it (with cache) and show.

### Funda RPM limit

I made 2 thinks do deal with requests per minute limitation of Funda API:

* in FundaClient component i throw FundaClientExceptionLimitExceeded
* in console script i use another counter with it's own limit, so i can use only part of the overall limit and leave the rest for another script.

Console script is starting every minute but only in a single instance, i use `flock` to prevent another one from running.

So, normally we should not see exceptions form FundaClient, but if they will appear - that would be a call to check our scripts.

### PSR autoload and Yii directory structure

Yii has his own naming conventions, so i decided to use them.
Of course, they are pretty different from PSR, so i made a small hack in index.php
to use both composer autoload and native one from Yii.

### External components

* See composer.json
* Mustache rendering yii extension: https://github.com/Haensel/EMustache

## Installation

You need:

* mysql with `insided` and `insided_test` databases and restored dump from `app/data/schema.mysql.sql`
* memcached
* php with proper extensions

Start on localhost with built-in php 5.5 server: `APPLICATION_ENV=dev php -S localhost:8888 -t public/ app/index.php`
Unit-tests: from app/tests `APPLICATION_ENV=dev ../../vendor/bin/phpunit unit`
Selenium tests: still in progress

## todo

* ~~write comments&readme - why i choose this approach?~~
* ~~tricky api - how to determine pages count?~~
* ~~general task - back-end~~
* ~~general task - front-end~~
* html5 animation
* ~~unit tests - memcache-based RPM limiter~~
* selenium tests - front web UI
* unit tests - console script common
* ~~running on production~~
* ~~memcache for rpm limit~~
* ~~console logging from yii~~
* psr codestyle
* ~~psr autoload/namespaces - integrate composer with yii autoloader~~
* code comments
* ~~repo on github~~
* ~~common config files~~
* ~~well-known template engine~~
* ~~remove all non-needed code everywhere~~
* salt for server side config
* capistrano/??? for deploy
