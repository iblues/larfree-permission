<?php
return [
    'detail' => [

        'id' => [
            'name' => 'id',
            'tip' => '',
            'type' => 'number',
            'sql_type' => 'bigint(20) unsigned',
        ],
        'name' => [
            'name' => 'name',
            'tip' => '',
            'type' => 'text',
            'sql_type' => 'varchar(191)',
        ],
        'guard_name' => [
            'name' => 'guard_name',
            'tip' => '',
            'type' => 'select',
            'option' => [
                'admin' => '后台权限',
            ],
            'default' => 'admin',
            'sql_type' => 'varchar(191)',
        ],
        'type' => [
            'name' => 'type',
            'tip' => '',
            'type' => 'select',
            'option' => [
                'api' => 'api权限',
                'nav' => '菜单权限',
                'button' => '按钮权限',
            ],
            'default' => 'admin',
            'sql_type' => 'varchar(255)',
        ],
        'created_at' => [
            'name' => '创建日期',
            'tip' => '',
            'type' => 'timestamp',
            'sql_type' => 'timestamp',
        ],
        'updated_at' => [
            'name' => 'updated_at',
            'tip' => '',
            'type' => 'timestamp',
            'sql_type' => 'timestamp',
        ],
    ],
];
