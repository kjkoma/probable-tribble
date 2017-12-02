<?php
/**
 * this file is defined constant varables.
 *
 */
return [
    'WNote' => [
        'App' => [
            'system_name' => 'Warehouse Note',
            'version'     => '1.0.0',
            'auther'      => 'Japan Computer Services, Inc',
            'description' => 'Warehouse Note - Easy IT Lifecycle management service',
            'site'        => 'wnote.jcslabs.net',
        ],
        'DB'  => [
            'Susers' => [
                'admin_kname' => 'WNOTE管理者',
                'token_length' => 128,
            ],
            'Sroles' => [
                'Kname' => [
                    'wnoteadmin' => 'WNOTEADMIN',
                    'sysadmin'   => 'SYSADMIN',
                    'syspublic'  => 'SYSPUBLIC',
                    'admin'      => 'ADMIN',
                    'public'     => 'PUBLIC',
                    'reference'  => 'REFERENCE',
                ],
                'RoleType' => [
                    'wnote'  => 0,
                    'system' => 3,
                    'domain' => 2,
                    'public' => 1,
                ],
            ],
            'SuserDomains' => [
                'DefaultDomain' => [
                    'default' => '1',
                    'not'     => '0',
                ],
            ],
            'Dsts' => [
                'invalid' => '0',
                'valid'   => '1',
                'notadd'  => '2',
            ],
        ],
        'Session'  => [
            'Auth' => [
                'is_login'  => 'Wnote.is_login',
                'id'        => 'Wnote.Auth.id',
                'name'      => 'Wnote.Auth.name',
                'email'     => 'Wnote.Auth.email',
                'user'      => 'Wnote.Auth.user',
            ],
            'App' => [
                'global'  => 'Wnote.App.global',
            ],
        ],
        'Cookie' => [
            'rememberme' => 'WNOTE_REMEMBERME',
        ],
        'JWT' => [
            'expired' => 43200, // 秒指定(12時間 = 60*60*12)
        ],
        'Config'  => [
        ],
        'Names' => [
            'dsts'  => 'DSTS',
            'dsts2' => 'DSTS-2',
        ],
    ],
];
