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
                    'sysgeneral' => 'SYSGENERAL',
                    'admin'      => 'ADMIN',
                    'general'    => 'GENERAL',
                    'reference'  => 'REFERENCE',
                ],
                'RoleType' => [
                    'wnote'   => 0,
                    'system'  => 3,
                    'domain'  => 2,
                    'general' => 1,
                ],
            ],
            'SuserDomains' => [
                'DefaultDomain' => [
                    'default' => '1',
                    'not'     => '0',
                ],
            ],
            'Sapps' => [
                'Kname' => [
                    'event'     => 'EVENT',
                    'instock'   => 'INSTOCK',
                    'picking'   => 'PICKING',
                    'stock'     => 'STOCK',
                    'stocktake' => 'STOCKTAKE',
                    'asset'     => 'ASSET',
                    'rental'    => 'RENTAL',
                ],
            ],
            'Companies' => [
                'CompanyKbn' => [
                    'all'      => '0',
                    'maker'    => '1',
                    'supplier' => '2',
                    'delivery' => '3',
                    'dest'     => '4',
                ],
            ],
            'Instock' => [
                'InstockKbn' => [
                    'new'    => '1',
                    'repair' => '2',
                    'back'   => '3',
                ],
                'InstockSts' => [
                    'not'      => '1',
                    'part'     => '2',
                    'complete' => '3',
                ],
            ],
            'Dsts' => [
                'invalid' => '0',
                'valid'   => '1',
                'notadd'  => '2',
            ],
            'Neighbor' => [
                'false' => '0',
                'true'  => '1',
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
