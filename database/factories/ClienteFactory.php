<?php

use Faker\Generator as Faker;

App\Cliente::truncate();

$factory->define(App\Cliente::class, function (Faker $faker) {
    return [
        'nombre' => $faker->firstName,
        'apellido' => $faker->lastName,
        'cedula' => $faker->unique()->randomNumber($nbDigits = NULL, $strict = false),
        'active' => $faker->numberBetween(0,1),
        'telefono' => $faker->phoneNumber,
        'telefono_op' => $faker->optional()->phoneNumber,
        'direccion' => $faker->address,
        'direccion_op' => $faker->optional()->address,
    ];
});