<?php

return [
    // Application info
    'app' => [
        'name' => '##{{NAME}}##',
        'description' => '##{{DESCRIPTION}}##',
        'author' => '##{{AUTHOR}}##',
        'version' => '##{{VERSION}}##'
    ],

    // Database config
    'database' => [
        'table_prefix' => '',
        'driver'    => 'mysql',
        'host'      => DB_HOST,
        'database'  => DB_NAME,
        'user'  => DB_USER,
        'password'  => DB_PASSWORD
    ],

    // Web Components config
    'webcomponents' => [
        'global_js_variable' => ''
    ]
];
