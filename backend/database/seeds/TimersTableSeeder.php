<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('timers')->insert(
            [
            'id' => 1,
            'name' => 'オンライン会話',
            'memo' => 'Erotalk in JapaneseでMatsu-sanと会話をする',
            'user_id' => '1',
            'started_at' => '2021-08-30 19:00:00',
            'stopped_at' => '2021-08-30 20:00:00',
            'created_at' => '2021-08-30 18:50:00',
            'updated_at' => '2021-08-30 20:00:00',
            'category_id' => '5',
            'category_name' => 'Online Lesson',
            'category_color' => '#9656a1',
        ]
        );

        //TODO: Factoryの使い方がわかったらテスト時にfactoryでダミーデータを10件くらい生成したい
        //factory(App\Timer::class, 10)->create();
    }
}
