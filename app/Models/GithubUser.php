<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-17 09:22:52
 * @LastEditTime: 2020-07-17 09:24:20
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Models/GithubUser.php
 * @Motto: MMMMMMMM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GithubUser extends Model
{
    //
    protected $table = 'github_user';
    protected $fillable = [
        'github_name',
        'github_id',
        'nickname',
        'display_name',
        'email',
        'avatar',
        'gravatar_id',
        'url',
        'html_url',
        'type',
        'site_admin',
        'company',
        'blog',
        'location',
        'hireable',
        'bio',
        'public_repos',
        'public_gists',
        'followers',
        'github_created_at',
        'github_updated_at',
    ];

    public function user() {
        $this->hasOne(User::class, 'github_id', 'id');
    }
}
