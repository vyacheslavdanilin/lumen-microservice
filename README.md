# Commands

## Run

docker-compose up -d 

docker exec php-fpm php artisan migrate

docker exec php-fpm php artisan queue:listen

## Stop

docker-compose down