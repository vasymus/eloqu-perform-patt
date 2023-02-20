# Project

Is based on [Laravel](https://laravel.com/docs).

## Environment

### Common

[Docker](https://docs.docker.com/get-docker/), [here](https://docs.docker.com/engine/install/ubuntu/), [here(for linux)](https://docs.docker.com/engine/install/linux-postinstall/) and [docker-compose](https://docs.docker.com/compose/) could be used for local and prod development. See installation guides: [https://docs.docker.com/engine/install/](https://docs.docker.com/engine/install/) and [https://docs.docker.com/compose/install/](https://docs.docker.com/compose/install/).

Run:

```shell
cp .env.example .env
```

If `docker-compose` fails try `docker compose` instead.

### Local

Run application (including [adminer](https://www.adminer.org/) on `localhost:8080` and database):

```shell
docker-compose up -d --build

# enter bash shell in app container
docker-compose exec app bash

# install php dependencies
composer install

# generate key
php artisan key:generate

# run migrations and seeders
php artisan migrate --seed
```

Open application on `localhost:9999`.

To view all possible routes see routes/web.php: `localhost:9999/users2`, `localhost:9999/posts` etc

Also see according implementations of what is described in lessons (see __materials/source folder and according video link). 
