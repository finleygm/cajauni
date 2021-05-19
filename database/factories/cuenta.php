<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cuenta;
use Faker\Generator as Faker;

$factory->define(Cuenta::class, function (Faker $faker) {
    return [
        'numero_cuenta' => '1234',
        'nombre_cuenta' => 'Certificados',
        'descripcion'=>'costo de 200bs',
        'precio_unitario'=>50
        
    ];
});
