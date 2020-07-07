<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-07 13:55:25
 * @LastEditTime: 2020-07-07 14:07:56
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/database/seeds/UserSeeder.php
 * @Motto: MMMMMMMM
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // DB::table('users')->insert([
        //     'name' => Str::random(10),
        //     'email' => Str::random(10).'@gmail.com',
        //     'password' => Hash::make('password'),
        // ]);
        factory(App\Models\User::class, 50)->create();
    }
}
