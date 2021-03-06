<?php

use Faker\Generator as Faker;

/*
 * Create an ICAO for use in the factory.
 */
if (!function_exists('createFactoryICAO')) {
    function createFactoryICAO(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $max = strlen($characters) - 1;
        $string = '';
        for ($i = 0; $i < 4; $i++) {
            try {
                $string .= $characters[random_int(0, $max)];
            } catch (Exception $e) {
            }
        }

        return $string;
    }
}

/*
 * Add any number of airports. Don't really care if they're real or not
 */
$factory->define(App\Models\Airport::class, function (Faker $faker) {
    $used = [];
    return [
        'id' => function () use ($used) {
            do {
                $string = createFactoryICAO();
            } while (in_array($string, $used, true));

            return $string;
        },
        'icao' => function (array $apt) {
            return $apt['id'];
        },
        'iata' => function (array $apt) {
            return $apt['id'];
        },
        'name'                 => $faker->sentence(3),
        'country'              => $faker->country,
        'timezone'             => $faker->timezone,
        'lat'                  => $faker->latitude,
        'lon'                  => $faker->longitude,
        'hub'                  => false,
        'ground_handling_cost' => $faker->randomFloat(2, 0, 500),
        'fuel_100ll_cost'      => $faker->randomFloat(2, 0, 100),
        'fuel_jeta_cost'       => $faker->randomFloat(2, 0, 100),
        'fuel_mogas_cost'      => $faker->randomFloat(2, 0, 100),
    ];
});
