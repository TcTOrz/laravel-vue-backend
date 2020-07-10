<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-09 10:11:35
 * @LastEditTime: 2020-07-10 14:14:08
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

use App\Exceptions\TryException;

// use App\Models\User;

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

    /**
     * 创建注册好的用户
     * @param $create
     * @return:
     */
    public function loginCreate($create) {
        $this->log('service.request to'.__METHOD__, ['create'=> $create]);
        try {
            \DB::beginTransaction();
            $this->userRepository->create($create);
            // $user = User::where('email', $create['email']);
            $userId = \DB::table('users')->where('email', $create['email'])->first()->id;
            $hid = Hashids::connection('user')->encode($userId);
            \DB::table('users')->where('id', $userId)->update(['hid'=> $hid]);

            $this->verifyEmail($create['email'], $create['name'], $userId);
            \DB::commit();
        } catch (\Exception $e) {
        //     //throw $th;
           $this->log("service.error to listener ".__METHOD__, ['message'=>$e->getMessage(), 'line'=>$e->getLine(), 'file'=>$e->getFile()]);
            \DB::rollBack();
            throw new TryException(json_encode($e->getMessage()), (int)$e->getCode());
        }

        $token = Hashids::connection('main')->encode([$userId, time()]);
        $data = new \stdClass();
        $data->token = $token;
        $data->hid = $hid;
        return $data;
    }

    /**
     * 邮箱验证
     * @param $userId
     * @return mixed
     */
    public function updateVerify($userId) {
        return $this->userRepository->update(['email_verified_at' => date("Y-m-d H:i:s")], $userId);
    }
}
