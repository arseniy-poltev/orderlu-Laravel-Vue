<?php

$factory->define(App\CourierCompany::class, function (Faker\Generator $faker) {
    return [
        "name" => $faker->name,
        "postmen_id" => $faker->name,
        "img_logo" => $faker->name,
    ];
});
