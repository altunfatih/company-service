## Docker
```

docker-compose run --entrypoint /bin/bash api
composer install
cp .env.example .env
php artisan key:generate

exit

docker-compose up -d
docker-compose run --entrypoint /bin/bash api
php artisan migrate:fresh --seed

```

## SWAGGER
http://localhost:8000/swagger
