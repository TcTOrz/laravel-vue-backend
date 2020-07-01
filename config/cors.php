<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // 'paths' => ['api/*'],
    'paths' => ['api/*', 'login'],

    'allowed_methods' => ['*'],  // 'POST,GET...'

    'allowed_origins' => ['*'], // http://10.10.10.61:8000, https://10.10.10.61:8000

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0, // 预检请求间隔时间

    'supports_credentials' => false, // 是否将请求暴露给页面，注意要和前端axios withCredentials值一致

];
