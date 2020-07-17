<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-16 14:24:34
 * @LastEditTime: 2020-07-16 14:40:18
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/database/migrations/2020_07_16_062349_github_user_table.php
 * @Motto: MMMMMMMM
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GithubUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if( !Schema::hasTable('github_user') ) {
            Schema::create('github_user', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('github_id')->default(0)->comment('github的ID');
                $table->string('nickname')->nullable()->comment('昵称');
                $table->string('github_name')->nullable()->comment('用户名');//对用 login
                $table->string('display_name')->nullable()->comment('别名');//对应 name
                $table->string('email')->nullable()->comment('邮箱');
                $table->string('avatar')->nullable()->comment('头像');
                $table->string('gravatar_id')->default('');
                $table->string('url')->nullable()->comment('github的api地址');
                $table->string('html_url')->nullable()->comment('github地址');
                $table->string('type')->nullable()->default('user')->comment('类型');
                $table->string('site_admin')->nullable()->default('false');
                $table->string('company')->nullable()->default('');
                $table->string('blog')->nullable()->default('');
                $table->string('location')->nullable()->default('');
                $table->string('hireable')->nullable()->default('');
                $table->string('bio')->nullable()->default('');
                $table->integer('public_repos')->nullable()->default(0);
                $table->integer('public_gists')->nullable()->default(0);
                $table->integer('followers')->nullable()->default(0);
                $table->string('github_created_at')->nullable()->default('');
                $table->string('github_updated_at')->nullable()->default('');
                $table->timestamps();
                $table->index('email');
                $table->index('github_name');
                $table->index('github_id');
                $table->index('display_name');
                $table->index('nickname');
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
        //
        Schema::dropIfExists('github_user');
    }
}
