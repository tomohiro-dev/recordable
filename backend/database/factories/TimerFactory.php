<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    $category_names = [
        'Grammar',
        'Speaking',
        'Writing',
        'Reading',
        'Online Session',
    ];
    $category_colors = [
        '#F582AE',
        '#8BD3DD',
        '#FF8906',
        '#E53170',
        '#00EBC7',
    ];
    return [
         'name' => $faker->realText($maxNbChars = 30, $indexSize = 1),
         'memo' => $faker->realText($maxNbChars = 140, $indexSize = 1),
         'user_id' => '1',
         'started_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
         'stopped_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
         'created_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
         'updated_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
         'category_id' => $faker->numberBetween($min = 1, $max = 5),
         'category_name' => $category_names[rand(0, 4)],
         'category_color' => $category_colors[rand(0, 2)],
    ];
});
