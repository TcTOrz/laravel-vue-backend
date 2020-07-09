<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-09 11:16:32
 * @LastEditTime: 2020-07-09 11:16:54
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Repositories/Eloquent/UserRepository.php
 * @Motto: MMMMMMMM
 */

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface {
    public function __construct() {}

    public function model() {
        return User::class;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function checkUserByEmail($email)
    {
        return User::where('email', $email)->first(); //->whereStatus('activated')
    }

    /**
     * 根据userId 获取user
     * @param $userId
     * @return mixed
     */
    public function first($userId)
    {
        return User::where('id', $userId)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return User::where('id', $id)->first();
    }
}

