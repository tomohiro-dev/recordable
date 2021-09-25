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
            'Grammar' => '#F582AE',
            'Speaking' => '#8BD3DD',
            'Writing' => '#FF8906',
            'Reading' => '#E53170',
            'Online Session' => '#00EBC7',
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
