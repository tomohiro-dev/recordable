<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        #初期化
        DB::table('users')->delete();

        #テストデータ挿入
        DB::table('users')->insert([
            'name' => 'user1 seeder',
            'email' => 'user1seeder@example.com',
            'password' => 'user1seeder',
            'remember_token' => str_random(10),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
