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
                    'recycle'   => 'RECYCLE'
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
            'Categories' => [
                'pc' => ['1'],
            ],
            'Assets' => [
                'AssetType' => [
                    'asset' => 1,
                    'count' => 2
                ],
                'AssetSts' => [
                    'new'      => 1,
                    'stock'    => 2,
                    'use'      => 3,
                    'rental'   => 4,
                    'repair'   => 5,
                    'abrogate' => 6,
                    'lost'     => 7,
                ],
                'AssetSubSts' => [
                    'other'    => 99,
                ],
                'AssetUseageType' => [
                    'normal' => 1,
                    'rental' => 2,
                ],
                'AssetUseageSts' => [
                    'use'    => 1,
                    'end'    => 2,
                ],
            ],
            'Instock' => [
                'InstockKbn' => [
                    'new'      => '1',
                    'repair'   => '2',
                    'exchange' => '3',
                    'back'     => '4',
                    'rental'   => '5',
                ],
                'InstockSts' => [
                    'not'       => '1',
                    'part'      => '2',
                    'complete'  => '3',
                    'cancelfix' => '8',
                    'cancel'    => '9'
                ],
                'InstockType' => [
                    'new'   => '1',
                    'asset' => '2',
                ]
            ],
            'Picking' => [
                'PickingKbn' => [
                    'new'        => '1',
                    'repair'     => '2',
                    'exchange'   => '3',
                    'rental'     => '4'
                ],
                'PickingSts' => [
                    'not'        => '1',
                    'work'       => '2',
                    'before'     => '3',
                    'complete'   => '4',
                    'cancelfix'  => '8',
                    'cancel'     => '9',
                ],
                'PickingType' => [
                    'asset' => '2',
                ]
            ],
            'Repair' => [
                'RepairKbn' => [
                    'stock'  => '1',
                    'useage' => '2'
                ],
                'RepairSts' => [
                    'instock_plan' => '1',
                    'instock'  => '2',
                    'repair'   => '3',
                    'picking'  => '4',
                    'stock'    => '5',
                    'abrogate' => '6',
                ],
            ],
            'Stocktake' => [
                'StocktakeSts' => [
                    'working'  => '1',
                    'complete' => '2',
                ],
                'StocktakeKbn' => [
                    'match'      => '1',
                    'incomplete' => '2',
                    'complete'   => '3',
                ],
                'StUnmatchKbn' => [
                    'match'   => '1',
                    'nostock' => '2',
                    'noitem'  => '3',
                ],
            ],
            'HistType' => [
                'instock'   => '1',
                'picking'   => '2',
                'stocktake' => '3',
                'entry'     => '4',
            ],
            'ReasonKbn' => [
                'instock'   => '1',
                'picking'   => '2',
                'stocktake' => '3',
                'entry'     => '4',
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
        'ListLimit' => [ // 一覧表示制限数
            'maxcount' => 500,
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
