<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-09 10:11:35
 * @LastEditTime: 2020-07-17 10:13:49
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Services/Auth/UserService.php
 * @Motto: MMMMMMMM
 */

namespace App\Services\Auth;

use App\Services\BaseService;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\GithubUserRepositoryInterface;

use App\Jobs\SendEmail;
use Vinkla\Hashids\Facades\Hashids;

use App\Exceptions\TryException;
use App\Repositories\Eloquent\GithubUserRepository;

// use App\Models\User;

class UserService extends BaseService {

    protected $userRepository;
    protected $githubUserRepository;

    public function __construct(UserRepositoryInterface $userRepository,
                                GithubUserRepositoryInterface $githubUserRepository) {
        $this->userRepository = $userRepository;
        $this->githubUserRepository = $githubUserRepository;
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
        return $this->userRepository->update(['email_verified_at' => date("Y-m-d H:i:s"), 'status' => 'activated'], $userId);
    }

    /**
     * 是否存在
     * @param $githubId
     * @return mixed
     */
    public function checkIsGithub($githubId) {
        return $this->githubUserRepository->getGithub($githubId);
    }

    /**
     * 存储数据
     * @param $user
     * @return mixed
     */
    public function storeGithub($user) {
        $data = $user->user;
        $create = [
            'github_name' => $data['login'],
            'github_id' => $data['id'],
            'nickname' => $user->nickname,
            'display_name' => $data['name'],
            'email' => $data['email'],
            'avatar' => $data['avatar_url'],
            'gravatar_id' => $data['gravatar_id'],
            'url' => $data['url'],
            'html_url' => $data['html_url'],
            'type' => $data['type'],
            'site_admin' => $data['site_admin'],
            'company' => $data['company'],
            'blog' => $data['blog'],
            'location' => $data['location'],
            'hireable' => $data['hireable'],
            'bio' => $data['bio'],
            'public_repos' => $data['public_repos'],
            'public_gists' => $data['public_gists'],
            'followers' => $data['followers'],
            'github_created_at' => $data['created_at'],
            'github_updated_at' => $data['updated_at'],
        ];
        try {
            \DB::beginTransaction();
            $this->log('service.request to '.__METHOD__, ['github_create'=> $create]);
            $result = $this->githubUserRepository->create($create);
            $userId = \DB::table('github_user')->where('github_id', $create['github_id'])->first()->id;
            \DB::commit();
        }catch(\Exception $e) {
            $this->log('"service.error" to listener '.__METHOD__, ['message'=>$e->getMessage(), 'line'=>$e->getLine(), 'file'=>$e->getFile()]);
            \DB::rollBack();
            throw new TryException(json_encode($e->getMessage(), (int)$e->getCode()));
        }
        // dd($userId);
        $now = time();
        $oanth = config('tctorz.oauth.auth.github');
        $param = [$userId, $now, $oanth];
        $auth = Hashids::connection('user')->encode($param);

        return redirect()->route('new.auth', ['auth'=> $auth]);
    }
}
