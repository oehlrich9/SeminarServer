# SeminarServer
Requirements:
php 
php-mysql
composer (https://getcomposer.org/)


For initialization, run following steps:
```
php composer install
```
and then from the cloned repository:
```
php artisan passport:client --personal
```
```
php artisan migrate
```

To start a development server run:
```
php artisan serve
```
and to perform any Analyse steps, run in another terminal from your work directory:
```
php artisan queue:work
```
