Hello!
Thank You for attention.

To start project you need to run:

* `docker-compose build`
* `docker-compose up -d`

To install dependencies you need to run:

* `docker-compose run php81-service composer install`
* `docker compose run node-service npm install; npm run build`

To run PHPUnit tests you need to run:

* `docker-compose run php81-service bin/phpunit` 

To prepare system to work we also need to set up DB:

* `docker-compose run php81-service bin/console doctrine:database:create` # creates database
* `docker-compose run php81-service bin/console messenger:setup-transports` # creates system-tables
* `docker-compose run php81-service bin/console messenger:consume` # start workers that consume queries