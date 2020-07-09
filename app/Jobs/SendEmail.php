<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-09 10:04:16
 * @LastEditTime: 2020-07-09 10:10:10
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Jobs/SendEmail.php
 * @Motto: MMMMMMMM
 */

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Mail\RegisterVerify;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $user;
    protected $subject = '验证邮件';
    protected $username;
    protected $verifyUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $user, $subject, $username, $verifyUrl)
    {
        $this->type = $type;
        $this->user = $user;
        $this->subject = $subject;
        $this->username = $username;
        $this->verifyUrl = $verifyUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch($this->type) {
            case 'verify_account':
                \Log::info('开始发送邮件');
                \Mail::to($this->user)->send(new RegisterVerify($this->subject, $this->username, $this->verifyUrl));
                break;
        }
    }
}
