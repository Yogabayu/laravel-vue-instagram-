

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

### Compile and Hot-Reload for Development

```sh
php artisan serve
```
#### Open other terminal

```sh
npm run dev
```
### File Penting 

```sh
api.php ->api route

index.js ->route js

cors.php -> setup cors
```


### Api documentation dan referensi

https://losdol.postman.co/workspace/My-Workspace~be2de025-464a-43c5-8b98-8400992dc016/collection/16070803-b4410a35-43e7-45d5-bbb8-92b51128c2b0?action=share&creator=16070803&active-environment=16070803-6f8b46d1-b2dc-4032-a9df-b7762f805009

https://hc200ok.github.io/vue3-easy-data-table-doc/  -> easydatatable
https://vuetifyjs.com/en/ ->base component vue3 
https://demos.themeselection.com/materio-vuetify-vuejs-admin-template/documentation/guide/laravel-integration/folder-structure.html  -->dasar template
