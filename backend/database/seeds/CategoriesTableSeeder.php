<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            'Reading' => '#8bd3dd',
            'Writing' => '#f582ae',
            'Listening' => '#f3d2c1',
            'Grammar' => '#ffd803',
            'Online Lesson' => '#9656a1',
        ];

        foreach ($categories as $name => $key) {
            DB::table('categories')->insert([
                'name' => $name,
                'color' => $key,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
