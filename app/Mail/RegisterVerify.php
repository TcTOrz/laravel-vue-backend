<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-08 16:48:48
 * @LastEditTime: 2020-07-09 09:57:49
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Mail/RegisterVerify.php
 * @Motto: MMMMMMMM
 */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterVerify extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = '验证邮件';
    public $username = '';
    public $verifyUrl = '';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $username, $verifyUrl)
    {
        $this->subject = $subject;
        $this->username = $username;
        $this->verifyUrl = $verifyUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->text('email.register');
        // return $this->from()
        return $this->markdown('email.register')
            ->subject($this->subject)
            ->with([
                'username' => $this->username,
                'url' => $this->verifyUrl
            ]);
    }
}
