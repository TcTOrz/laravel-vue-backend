<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-07 13:57:38
 * @LastEditTime: 2020-07-07 13:57:39
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/database/seeds/DatabaseSeeder.php
 * @Motto: MMMMMMMM
 */

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
    }
}
