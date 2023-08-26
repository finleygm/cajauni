
## Sistema de tesoreria y comercializacion

Este sistema incluye las unidades de tesoreria y comercializacion para realizar la venta de producto por unidades, y rubro, se desarrollo utilizando vue y laravel

## Requisitos
- Composer version 2.5.8
- PHP 7.4.26
- Laravel Framework 5.8.38
- MySql 5.7.36
- Node v16.15.1
- Vue 2.5.17,

## Instalación
Para su instalación ejecutar des la carpeta raiz
```console
npm install
composer install
```
##Configuración
Tambien se ejecutar crear una base de datos llamada caja_uni con el archivo caja_u.sql
y copiar el archivo .env.example como .env y configurar las siguientes propiedades
```properties
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=caja_uni
DB_USERNAME=root
DB_PASSWORD=
```
## Ejecución
Desde la carpeta raiz ejecutar
```console
npm run dev
php artisan serve
```



