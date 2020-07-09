<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-09 10:11:35
 * @LastEditTime: 2020-07-09 11:15:02
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Services/Auth/UserService.php
 * @Motto: MMMMMMMM
 */

namespace App\Services\Auth;

use App\Services\BaseService;
use App\Repositories\Contracts\UserRepositoryInterface;

use App\Jobs\SendEmail;
use Vinkla\Hashids\Facades\Hashids;

class UserService extends BaseService {

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return $this->userRepository->getUserById($id);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function checkUserByEmail($email) {
        return $this->userRepository->checkUserByEmail($email);
    }

    public function checkPwd($requestPwd, $sqlPwd) {
        if( !password_verify($requestPwd, $sqlPwd) ) {
            $this->setCode(config('validation.login')['login.error']);
            // TODO
            return $this->response();
        }
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function findUserByToken($userId) {
        return $this->userRepository->first($userId);
    }

    public function verifyEmail($email, $name, $id) {
        $message = [$id, time(), 3];
        $param = Hashids::connection('code')->encode($message);
        $this->log('service.note to'.__METHOD__, ['note'=>'邮件发送队列']);
        dispatch((new SendEmail('verify_account',
            $email,
            '邮箱激活',
            $name,
            config('app.url').'/verify?token='.$param))->onQueue('send-email'));
        return true;
    }
}
