<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-07 15:17:30
 * @LastEditTime: 2020-07-20 10:28:22
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Models/User.php
 * @Motto: MMMMMMMM
 */

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\GithubUser;

// use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;//, SoftDeletes;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function phone() {
        return $this->hasOne('App\Models\Phone', 'another_id', 'id');
    }

    public function cs()
    {
        return $this->hasMany('App\Models\Phone', 'another_id', 'id');
        // foreach ($c as $s) {
        //     var_dump($s);
        // }
        // return $this->hasMany('App\Models\Phone', 'another_id', 'id');
    }

    public function github() {
        return $this->hasOne(GithubUser::class, 'id', 'github_id');
    }
}
