<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-07 15:21:08
 * @LastEditTime: 2020-07-07 16:11:17
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Models/Phone.php
 * @Motto: MMMMMMMM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Phone extends Model
{
    use Notifiable;
    //
    protected $table = 'phone';

    public function user() {
        return $this->belongsTo('App\Models\User', 'another_name', 'name');
    }
}
