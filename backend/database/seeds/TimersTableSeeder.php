<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('timers')->insert([
        //     'id' => 1,
        //     'name' => 'オンライン会話',
        //     'memo' => 'Erotalk in JapaneseでMatsu-sanと会話をする',
        //     'user_id' => '1',
        //     'started_at' => '2021-08-30 19:00:00',
        //     'stopped_at' => '2021-08-30 20:00:00',
        //     'created_at' => '2021-08-30 18:50:00',
        //     'updated_at' => '2021-08-30 20:00:00',
        //     'category_id' => '3',
        //     'category_name' => 'Speaking',
        //     'category_color' => '#FFC46D',
        // ],
        // [
        //     'id' => 2,
        //     'name' => '日本語文法学習',
        //     'memo' => '「深めてわかる！日本語文法」を進める',
        //     'user_id' => '1',
        //     'started_at' => '2021-08-30 21:30:00',
        //     'stopped_at' => '2021-08-30 23:30:00',
        //     'created_at' => '2021-08-30 21:30:00',
        //     'updated_at' => '2021-08-30 23:30:00',
        //     'category_id' => '3',
        //     'category_name' => 'Writing',
        //     'category_color' => '#FA0202',
        // ],
        // [
        //     'id' => 3,
        //     'name' => '日本語作文',
        //     'memo' => 'Matsu-sanからもらった課題を進める',
        //     'user_id' => '1',
        //     'started_at' => '2021-08-30 23:30:00',
        //     'stopped_at' => '2021-08-31 00:00:00',
        //     'created_at' => '2021-08-30 23:30:00',
        //     'updated_at' => '2021-08-31 00:00:00',
        //     'category_id' => '3',
        //     'category_name' => 'Grammar',
        //     'category_color' => '#5735CE',
        // ]);

        //TODO:テスト時にdactoryでダミーデータを100件くらい生成したい
    }
}