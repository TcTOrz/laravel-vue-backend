<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-09 10:14:41
 * @LastEditTime: 2020-07-20 11:13:42
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/config/hashids.php
 * @Motto: MMMMMMMM
 */

/**
 * Copyright (c) Vincent Klaiber.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vinkla/laravel-hashids
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        'main' => [
            'salt' => env('HASH_MAIN', 'tctorzMain'),
            'length' => '50',
        ],

        'code' => [
            'salt' => env('HASH_MAIN', 'tctorzCode'),
            'length' => '50',
        ],

        'user' => [
            'salt' => env('HASH_USER', 'tctorzUser'),
            'length' => '30',
        ],

        'console_token' => [
            'salt' => env('LOGIN_SALT', 'ConsoleLoginWithTctorz'),
            'length' => '50',
        ],

        'alternative' => [
            'salt' => 'your-salt-string',
            'length' => 'your-length-integer',
        ],

    ],

];
