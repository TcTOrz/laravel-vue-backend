<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService extends BaseService {

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
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
}
