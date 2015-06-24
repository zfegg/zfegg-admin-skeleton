<?php

return array(
    'zfegg_admin'     => array(
        'db_adapter'                     => array(
            'driver'   => 'Pdo',
            'dsn'      => 'mysql:dbname=zfegg-admin;host=localhost;charset=utf8',
            'username' => 'root',
            'password' => '',
        ),
        'authentication_adapter' => array(
            'DbTable' => array(
                'db' => 'Zfegg\Admin\DbAdapter',
                'table_name' => 'admin_user',
                'identity_column' => 'account',
                'credential_column' => 'password',
                'credential_treatment' => 'md5(?)',
            ),
        ),
    ),

);
