<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Reading' => '#696969',
            'Writing' => '#696969',
            'Listening' => '#696969',
            'Grammar' => '#696969',
            'Online Lesson' => '#696969'
        ];

        foreach ($categories as $name => $key) {
            DB::table('categories')->insert([
                'name' => $name,
                'color' => $key,
                'user_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}