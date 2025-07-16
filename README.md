

## Project Setup

```sh
composer install
npm install

php artisan migrate:fresh --seed

php artisan passport:install
php artisan key:generate
php artisan storage:link
php artisan optimize
```
