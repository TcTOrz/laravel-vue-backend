<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface extends BaseRepositoryInterface {
    /**
     * 通过邮箱或者正常状态的用户
     * @param $email
     * @return mixed
     */
    public function checkUserByEmail($email);

    /**
     * 根据userId 获取user
     * @param $userId
     * @return mixed
     */
    public function first($userId);
}
