<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-16 14:14:19
 * @LastEditTime: 2020-07-16 14:14:20
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Providers/RepositoryServiceProvider.php
 * @Motto: MMMMMMMM
 */

namespace App\Providers;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Contracts\GithubUserRepositoryInterface;
use App\Repositories\Eloquent\GithubUserRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(GithubUserRepositoryInterface::class, GithubUserRepository::class);
    }
}
