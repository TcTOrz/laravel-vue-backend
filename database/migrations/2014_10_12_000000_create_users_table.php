<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-16 14:18:01
 * @LastEditTime: 2020-07-16 14:46:52
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/database/migrations/2014_10_12_000000_create_users_table.php
 * @Motto: MMMMMMMM
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if( !Schema::hasTable('users') ) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('hid')->default('')->comment('加密ID');
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->string('status')->default('false');
                // $table->enum('status',['true','false'])->default('false');
                $table->integer('github_id')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
