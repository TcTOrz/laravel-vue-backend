<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-09 11:11:27
 * @LastEditTime: 2020-07-17 09:54:50
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/config/tctorz.php
 * @Motto: MMMMMMMM
 */

return [
    'token' => [
        'valid_time' => 12 * 60 * 60,
        'login_way' => ['github'],
    ],
    'verify' => [
        'valid_time' => 24 * 60 * 60,
    ],
    'oauth' => [
        'login' => [
            1 => 'github_id',
        ],
        'auth' => [
            'github' => 1,
        ],
    ],
];
